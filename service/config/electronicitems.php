<?php

use App\Models\ElectronicItem;

const MAX_EXTRAS = 'maxExtras';
const CAN_BE_SOLD_STANDALONE = 'canBeSoldStandalone';

return [
    ElectronicItem::ELECTRONIC_ITEM_CONSOLE    => [
        'items'                => [
            [
                'id'    => 1,
                'name'  => 'Basic Console',
                'price' => 1900
            ]
        ],
        MAX_EXTRAS             => 4,
        CAN_BE_SOLD_STANDALONE => true
    ],
    ElectronicItem::ELECTRONIC_ITEM_CONTROLLER => [
        'items'                => [
            [
                'id'         => 1,
                'price'      => 100,
                'name'       => 'Basic wired controller',
                'isWireless' => false
            ],
            [
                'id'         => 2,
                'price'      => 200,
                'name'       => 'Basic wireless controller',
                'isWireless' => true
            ],
        ],
        MAX_EXTRAS             => 0,
        CAN_BE_SOLD_STANDALONE => false
    ],
    ElectronicItem::ELECTRONIC_ITEM_MICROWAVE  => [
        'items'                => [
            [
                'id'    => 1,
                'price' => 500,
                'name'  => 'Standard microwave'
            ]
        ],
        MAX_EXTRAS             => 0,
        CAN_BE_SOLD_STANDALONE => true,
    ],
    ElectronicItem::ELECTRONIC_ITEM_TELEVISION => [
        'items'                => [
            [
                'id'    => 1,
                'price' => 500.5,
                'name'  => 'Standard television 1'
            ],
            [
                'id'    => 2,
                'price' => 1000.35,
                'name'  => 'Standard television 2'
            ]
        ],
        MAX_EXTRAS             => -1, // less than zero means we can have multiple televisions
        CAN_BE_SOLD_STANDALONE => true,
    ]
];