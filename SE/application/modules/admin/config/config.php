<?php
return   ['db' => ['dsn' => 'mysql:dbname=partner;host=192.168.61.2;port=3306',
        'username' => 'admin',
        'passwd' => 'adwdwad125',
        'options' => [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                      PDO::ATTR_TIMEOUT => 2,
                      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
        'charset'=>'utf8',
        'tbprefix' => 'admin_',
    ],
    
];


