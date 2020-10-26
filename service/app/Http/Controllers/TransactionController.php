<?php


namespace App\Http\Controllers;


use App\Exceptions\ExtraNotAllowed;
use App\Exceptions\MaxExtrasExceeded;
use App\Models\Contracts\ExtrasAddableInterface;
use App\Models\Contracts\ExtrasInterface;
use app\Models\Transaction\LineItem;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\TransactionFactory;
use App\Repositories\ElectronicItemsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Laravel\Lumen\Routing\Router;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;


class TransactionController extends BaseController
{
    use SelfCallableTrait;

    public function __construct(
        ElectronicItemsRepository $repositoryOfAllElectronicItems,
        TransactionFactory $transactionFactory
    ) {
        $this->repositoryOfAllElectronicItems = $repositoryOfAllElectronicItems;
        $this->transactionFactory = $transactionFactory;
    }

    public function __invoke(Router $router)
    : void {
        $router->addRoute('POST', '/transaction', $this->call('postTransaction'));
    }

    public function postTransaction(Request $request)
    {
        $content = $request->getContent();
        $electronicItems = $this->getElectronicItemsForTransaction(json_decode($content, true));
        $transaction = $this->transactionFactory->createTransaction($electronicItems);
        $serializedTransaction = $this->serializeTransactionForPresentation($transaction);

        return new JsonResponse(
            [
                'type' => 'transaction',
                'data' => $serializedTransaction
            ]
        );
    }

    private function serializeTransactionForPresentation(Transaction $transaction)
    : array {
        $serializedTransaction = [];

        $serializedTransaction['price'] = $transaction->getTotalPrice();
        $serializedTransaction['lineItems'] = [];

        collect($transaction->getLineItems())->each(
            function (LineItem $lineItem) use (&$serializedTransaction) {
                $electronicItem = $lineItem->getItem();

                $serializedLineItem = [
                    'totalPrice' => $lineItem->getTotalPrice(),
                    'itemName'   => $electronicItem->getName(),
                    'itemPrice'  => $electronicItem->getPrice(),
                    'quantity'   => $lineItem->getQuantity()
                ];

                if ($electronicItem instanceof ExtrasAddableInterface && $electronicItem->hasExtras()) {
                    $serializedLineItem['contains'] = [];

                    collect($electronicItem->getExtras())->each(
                        function (array $extra) use (&$serializedLineItem) {
                            $serializedLineItem['contains'][] = [
                                'quantity' => $extra['quantity'],
                                'name'     => $extra['item']->getName(),
                                'price'    => $extra['item']->getPrice()
                            ];
                        }
                    );
                }

                $serializedTransaction['lineItems'][] = $serializedLineItem;
            }
        );

        return $serializedTransaction;
    }

    private function getElectronicItemsForTransaction(array $payload)
    : array {
        $transactionItems = [];

        if (!array_key_exists('data', $payload) || !array_key_exists('items', $payload['data']) ||
            $payload['type'] !== 'order') {
            throw new NotAcceptableHttpException('Incorrect Payload');
        }

        $items = $payload['data']['items'];

        foreach ($items as $item) {
            $itemParameterBag = $this->getParameterBagForItemPayload($item);
            $type = $itemParameterBag->get('type');
            $id = $itemParameterBag->get('id');
            $quantity = $itemParameterBag->get('quantity');
            $extras = $itemParameterBag->get('extras', []);

            $electronicItem = $this->repositoryOfAllElectronicItems->getItemByTypeAndId($type, $id);

            if (!$electronicItem) {
                throw new NotAcceptableHttpException("Item not found for type: $type id: $id");
            }

            if (count($extras) > 0 && !$electronicItem instanceof ExtrasAddableInterface) {
                throw new NotAcceptableHttpException("Extras could not be added for $type");
            }

            foreach ($extras as $extra) {
                $extraParameterBag = $this->getParameterBagForItemPayload($extra);
                $extraType = $extraParameterBag->get('type');
                $extraId = $extraParameterBag->get('id');
                $extraQuantity = $extraParameterBag->get('quantity');

                $extraElectronicItem = $this->repositoryOfAllElectronicItems->getItemByTypeAndId($extraType, $extraId);

                if (!$extraElectronicItem || !$extraElectronicItem instanceof ExtrasInterface) {
                    throw new NotAcceptableHttpException(
                        "Extras of type " . $extraElectronicItem->getType(
                        ) . " could not be added for $type with id $id"
                    );
                }

                try {
                    $electronicItem->addExtra($extraElectronicItem, $extraQuantity);
                } catch (ExtraNotAllowed $e) {
                    throw new NotAcceptableHttpException(
                        $e->getType() . " can not be added as an extra for " . $e->getClass()
                    );
                } catch (MaxExtrasExceeded $e) {
                    throw new NotAcceptableHttpException(
                        "Extras for " . $electronicItem->getType() . " (id: " . $electronicItem->getId(
                        ) . ") have exceeded the max limit of " . $e->getMaxExtras()
                    );
                }
            }

            $transactionItems[] = [
                'item'     => $electronicItem,
                'quantity' => $quantity
            ];
        }


        return $transactionItems;
    }

    /**
     * @param $item
     * @return ParameterBag
     */
    private function getParameterBagForItemPayload($item)
    : ParameterBag {
        $itemParameterBag = new ParameterBag($item);

        if (!$itemParameterBag->has('type') || !$itemParameterBag->has('quantity') || !$itemParameterBag->has(
                'id'
            )) {
            throw new NotAcceptableHttpException('Incorrect Payload');
        }
        return $itemParameterBag;
    }

    private ElectronicItemsRepository $repositoryOfAllElectronicItems;
    private TransactionFactory $transactionFactory;
}