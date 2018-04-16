<?php

// 设置返回json格式数据
header('content-type:application/json;charset=utf8');

$fn = 'pic/abc';

if (!is_dir($fn)) {
    mkdir($fn, 0777, true);
}
