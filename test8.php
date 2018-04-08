<?php
// 设置返回json格式数据
header('content-type:application/json;charset=utf8');

/**
 *
 */
class CN
{

    public $abc;

    public function CN($a)
    {
        $this->abc = $a;
    }

    // 析构方法
    public function __destruct()
    {
        echo $this->abc;
    }

}

function FN()
{
    $a = new CN('try');

    echo " go";
}

FN();

echo " _end";
