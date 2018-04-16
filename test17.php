<?php

// 设置返回json格式数据
header('content-type:application/json;charset=utf8');

$a = [];

$a['abc'][1231] = '123';
$a['abc'][32]   = 'ad';

$a['tr'] = 223;
$a['t']  = 4;

print_r(array_values($a));

foreach ($a as $k1 => $v1) {
    if (is_array($v1)) {
        foreach ($v1 as $k2 => $v2) {
            print_r($v2 . ' , ');
        }
    } else {

        print_r($v1 . ' , ');
    }
}
