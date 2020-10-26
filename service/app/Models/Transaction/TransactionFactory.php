<?php


namespace App\Models\Transaction;


use Symfony\Component\HttpFoundation\ParameterBag;

class TransactionFactory
{
    public function createTransaction(array $electronicItems)
    : Transaction {
        $transaction = new Transaction();

        collect($electronicItems)->each(
            function (array $item) use ($transaction) {
                $itemParameterBag = new ParameterBag($item);
                $quantity = $itemParameterBag->get('quantity', 1);
                $electronicItem = $itemParameterBag->get('item', null);

                if (!$electronicItem) {
                    return;
                }

                $transaction->addLineItem(new LineItem($electronicItem, $quantity));
            }
        );

        return $transaction;
    }
}