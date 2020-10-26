<?php


namespace App\Http\Controllers;


use App\Models\Contracts\ExtrasAddableInterface;
use App\Models\Contracts\WirelessInterface;
use App\Models\ElectronicItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Laravel\Lumen\Routing\Router;

class PortalController extends BaseController
{
    use SelfCallableTrait;

    public function __construct(
        string $baseUrl,
        array $electronicItems
    ) {
        $this->electronicItems = $electronicItems;
        $this->baseUrl = $baseUrl;
    }

    public function __invoke(Router $router)
    {
        $router->addRoute(['GET', 'OPTIONS'], '/api/portal', $this->call('getPortal'));
    }

    public function getPortal(Request $request)
    {
        $serializedElectronicItems = [];

        foreach ($this->electronicItems as $electronicItem) {
            if (!array_key_exists($electronicItem->getType(), $serializedElectronicItems)) {
                $serializedElectronicItems[$electronicItem->getType()] = [
                    MAX_EXTRAS             => $electronicItem instanceof ExtrasAddableInterface ?
                        $electronicItem->maxExtras() : 0,
                    CAN_BE_SOLD_STANDALONE => $electronicItem->canBeSoldStandalone(),
                    'allowedExtraTypes'    => $electronicItem instanceof ExtrasAddableInterface ?
                        $electronicItem->allowedExtraTypes() : [],
                    'items'                => []
                ];
            }

            $serializedElectronicItems[$electronicItem->getType()]['items'][] = [
                'price'      => $electronicItem->getPrice(),
                'name'       => $electronicItem->getName(),
                'id'         => $electronicItem->getId(),
                'isWireless' => $electronicItem instanceof WirelessInterface && $electronicItem->isWireless(),
            ];
        }

        return new JsonResponse(
            [
                'type'  => 'portal',
                'data'  => [
                    'electronicItems' => $serializedElectronicItems
                ],
                'links' => [
                    'transaction' => $this->baseUrl . "/transaction"
                ]
            ]
        );
    }

    private string $baseUrl;

    /**
     * @var ElectronicItem[]
     */
    private array $electronicItems;
}