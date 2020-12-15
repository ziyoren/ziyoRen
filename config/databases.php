<?php
return [

    // [必需的配置项]
    'database_type' => 'mysql',  //Driver
    'database_name' => 'test',   //数据库名称
    'server' => 'localhost',     //服务器地址
    'username' => 'root',        //用户名
    'password' => '',            //数据库密码

    // [可选配置]
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
    'port' => 3306,

    // [可选配置] 表名前缀，默认为空
    'prefix' => '',

    // [可选配置] 是否启用日志
    'logging' => false,

    // [可选配置] MySQL socket (shouldn't be used with server and port)
    'socket' => null,  // '/tmp/mysql.sock',

    // [可选配置] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
    // 'option' => [
    //     PDO::ATTR_CASE => PDO::CASE_NATURAL
    // ],
    'option' => [],

    // [可选配置] Medoo will execute those commands after connected to the database for initialization
    // 'command' => [
    //     'SET SQL_MODE=ANSI_QUOTES'
    // ],
    'command' => [],

    // [可选配置] 连接池数量
    'size' => 64,
];