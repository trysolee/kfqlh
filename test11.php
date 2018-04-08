<?php
// 设置返回json格式数据
header('content-type:application/json;charset=utf8');

/**
 *
 */
class p1
{

    public $arr = ['长度' => 23,

        '面积'                => 456];

    private  static function f1($value, $key)
    {
        print_r($key . '->' . $value . '<BR>'); 
    }

    public function pr()
    {
        array_walk($this->arr, 'p1::f1');
    }
}

// function f1($value, $key)
// {
//     print_r($key . '->' . $value . '<BR>');
// }

$p = new p1();
$p->pr();
