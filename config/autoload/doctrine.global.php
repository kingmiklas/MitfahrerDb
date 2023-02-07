<?php

return [
    'doctrine' => [
        'configuration' => [
            'orm_default' => [
                'driver' => 'orm_default'
            ]
        ],

        'connection' => [
            'orm_default' => [
                'driver' => 'pdo_mysql',
                'params' => [
                    'host' => '127.0.0.1',
                    'port' => '3329',
                    'user' => 'root',
                    'password' => '',
                    'dbname' => 'MitfahrerDB',
                ],
            ],
        ],

        'driver' => [
            'orm_default' => [
                'class' => Doctrine\ORM\Mapping\Driver\AttributeDriver::class,
                'paths' => [
                    __DIR__ . '/../../src/App/Entity',
                ],
            ],
        ],

        'entitymanager' => [
            'orm_default' => [
                'connection' => 'orm_default',
                'configuration' => 'orm_default',
            ],
        ],
    ],
];
