<?php

include_once '/tools/sdb.php';

// 设置返回json格式数据
header('content-type:application/json;charset=utf8');

$d = [
    'name'  => 'val',
    'DAT'   => [
        'JSON' => [
            'abc' => 'rty78',
        ],
    ],
    'WHERE' => [
        'name' => 'abc',
    ],
];

SDB::update($d);
