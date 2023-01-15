<?php

declare(strict_types=1);

return [
    'vat' => env(key: 'ARC_VAT', default: false),
    'profit_margin' => env(key: 'ARC_PROFIT_MARGIN', default:1.4),
    'shift_end_time' => env(key: 'ARC_SHIFT_END_TIME', default: '08:00:00'),
    'shift_start_time' => env(key: 'ARC_SHIFT_START_TIME', default:'08:01:00'),
    'counter_default_product_quantity' => env(key: 'ARC_COUNTER_DEFAULT_PRODUCT_QUANTITY'),
];
