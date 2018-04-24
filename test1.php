<?php

// 设置返回json格式数据
header('content-type:application/json;charset=utf8');

$f = false;

$t = true;

if ($a = $f) {
    echo "string";
}


echo gettype($f);