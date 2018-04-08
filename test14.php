<?php
// 设置返回json格式数据
header('content-type:application/json;charset=utf8');

$a1=array("a"=>"red","b"=>"green","c"=>"blue");
$a2=array("e"=>"red","f"=>"green","g"=>"blue","c"=>"abcblue");

$result=array_diff($a1,$a2);
print_r($result);
