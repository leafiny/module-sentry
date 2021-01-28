<?php

$config = [
    'model' => [
        'log_file' => [
            'class' => Sentry_Log_Model_File::class
        ],
    ],

    'observer' => [
        'setup_object_init_after' => [
            'sentry_init' => 0,
        ],
    ],

    'event' => [
        'sentry_init' => [
            'class' => Sentry_Log_Observer_Init::class
        ],
    ],
];
