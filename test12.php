<?php
// 设置返回json格式数据
header('content-type:application/json;charset=utf8');

/**
 *
 */
class CN
{

    public static $ac = 3;
}

$GLOBALS['ag'] = 'something';

$b = &CN::$ac;

$b = ['1', 'r'];

$arr = [
    '110' => ['小名', '大门', 'ok'],
    '231' => ['他名', 'abc', '34'],
];

function F1($value, $key)
{
    print_r($value . '<BR>');
}

array_walk_recursive($arr, 'F1');

print_r(CN::$ac);
