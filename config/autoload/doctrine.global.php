<?php

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'params' => [
                    'driver' => 'pdo_mysql',
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
                    __DIR__ . '/../../module/App/Entity',
                ],
            ],
        ],
    ],
];
