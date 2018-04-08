<?php
// 设置返回json格式数据
header('content-type:application/json;charset=utf8');

$a = [

    'p1' => [

        'b2' => 23,
    ],
];

if (empty($a['p2']['b3'])) {
    print_r('isNull');

}
