<?php
// 设置返回json格式数据
header('content-type:application/json;charset=utf8');

$a = 'abc';

$arr = [$a];

function Ex()
{

	print_r(123);
    exit();
}

Ex();

print_r($arr);
