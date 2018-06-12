<?php

// 设置返回json格式数据
header('content-type:application/json;charset=utf8');

$f = [
    'go' => function () {
        return 'abc';
    },
];

/**
 *
 */
class Ca
{

    public static $aa;

    public function hh()
    {

        $d = Ca::$aa;
        print_r($d['go']());
    }

}

Ca::$aa = $f;

// print_r($f['go']());
//
$d = Ca::$aa;

// print_r(Ca::$aa['go']());

// print_r($d['go']());
//
Ca::hh();
