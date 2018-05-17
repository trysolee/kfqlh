<?php

// 设置返回json格式数据
header('content-type:application/json;charset=utf8');

$f = [];

$t = 'abc';

$f['try'] = [$t];

print_r($f) ;
