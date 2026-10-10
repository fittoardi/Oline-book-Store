<?php

return [
    'brand' => [
        'name' => 'Kembang Books',
        'tagline' => 'Stories for every state of mind.',
        'email' => 'hello@kembangbooks.test',
        'description' => 'Kembang Books curates fiction, nonfiction, business, children, and poetry books for curious minds.',
    ],

    'shipping' => [
        'cost' => 15000,
        'free_threshold' => 300000,
        'courier' => 'JNE / J&T',
        'estimate' => '2-4 business days',
    ],

    'payment_channels' => ['Bank transfer', 'E-wallet', 'QRIS', 'COD'],

    'support' => [
        'hours' => 'MON - SAT / 09:00 - 17:00',
        'timezone' => 'WIB',
        'response_time' => 'under 2 hours',
        'topics' => ['Book recommendations', 'Order & payment support', 'Delivery & gifts'],
    ],

    'home' => [
        'announcement' => 'KIN. STORE. COUPONS.',
        'stats' => [
            'reader_rating' => 4.8,
            'carefully_packed' => 100,
        ],
    ],
];
