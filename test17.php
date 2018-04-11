<?php

// 设置返回json格式数据
header('content-type:application/json;charset=utf8');

$a = [];

$a['abc'][1231]= '123';

print_r($a);
