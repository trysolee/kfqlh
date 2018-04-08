<?php

include_once "getdb.php";


// 设置返回json格式数据
header('content-type:application/json;charset=utf8');

/**
 *
 */
$DB  = new getDB();

$D = $DB->SQL('select * from pro_in where INID = 123 ');




$s = json_encode($D, JSON_UNESCAPED_UNICODE);

echo $s;

// print_r($s);
