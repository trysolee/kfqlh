<?php
// 设置返回json格式数据
header('content-type:application/json;charset=utf8');


$a1=array("a"=>"red2","b"=>"green","d"=>"blue");
$a2=array("a"=>"red1","c"=>"blue","d"=>"pink");

$result=array_intersect_key($a1,$a2);
print_r($result);
