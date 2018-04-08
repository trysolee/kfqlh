<?php

// 设置返回json格式数据
header('content-type:application/json;charset=utf8');

/**
 *
 */
class abc
{

    public $a = 1;

}

$好事 = new abc();

$w = $好事;

$w->a = 3;

print_r($好事->a);
