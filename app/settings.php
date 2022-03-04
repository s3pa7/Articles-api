<?php


declare(strict_types=1);

return [
    'db'       => [
        'host'     => getenv('MYSQL_HOST', false),
        'username' => getenv('MYSQL_USER', false),
        'password' => getenv('MYSQL_PASSWORD', false),
        'database' => getenv('MYSQL_DATABASE', false),
    ],

];
