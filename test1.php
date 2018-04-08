<?php

include_once "getdb.php";

// 设置返回json格式数据
header('content-type:application/json;charset=utf8');


$R = new getDB();

$pro = $R->proByID(3) ;

print_r($pro['JSON']);

