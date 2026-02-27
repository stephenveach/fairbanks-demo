<?php

return [
    'driver' => env('CART_DRIVER', 'session'),
    'connection' => env('CART_DB_CONNECTION', null),
    'table' => 'cart_items',
    'session_key' => 'shopping_cart',
];
