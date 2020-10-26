<?php

namespace App\Repositories;


use App\Models\ElectronicItem;

class ElectronicItemsRepository
{
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * Returns the items depending on the sorting type requested
     *
     * @param bool $desc
     * @return ElectronicItem[]
     */
    public function getSortedItems(bool $desc)
    : array {
        $items = [];
        collect($this->items)->each(
            function (ElectronicItem $item) use (&$items) {
                $items[$item->getTotalPriceWithoutDecimals()] = $item;
            }
        );

        return collect($items)->sortKeys(SORT_NUMERIC, $desc)->toArray();
    }

    /**
     * @param string $type
     * @return ElectronicItem[]
     */
    public function getItemsByType(string $type)
    : array {
        return collect($this->items)->filter(
            function (ElectronicItem $item) use ($type) {
                return $item->is($type);
            }
        )->toArray();
    }

    public function getItemByTypeAndId(string $type, string $id)
    : ?ElectronicItem {
        return collect($this->items)->first(
            function (ElectronicItem $item) use ($type, $id) {
                return $item->is($type) && $item->getId() === $id;
            }
        );
    }

    private array $items;

}