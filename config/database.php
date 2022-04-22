<?php
return [

    'default' => 'mysql',

    'connections' => [
        'api' => [
            'driver'    => 'mysql',
            'host'      => '127.0.0.1',
            'port'      =>  3306,
            'database'  => 'api',
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],
    ],
];
