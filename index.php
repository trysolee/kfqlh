<?php
//  echo 'Hello World OK';

// 设置返回json格式数据
header('content-type:application/json;charset=utf8');

$a = new ret();
$a->toJsonStr();

// $db = [

//     [
//         'name' => 'pro_work',
//         'step' => [],
//         'role' => [],
//         'sql' => "SELECT b . *"
//         . "FROM pro_user AS a, pro_work AS b"
//         . "WHERE a.UID =1"
//         . "AND a.JID = b.JID"
//         . "AND b.FT > '2018-01-20'"
//         . "AND b.CT > '2018-01-30'"
//         . "LIMIT 0 , 30",
//     ], [
//         'name' => 'user',
//         'step' => [],
//         'role' => [],
//         'sql' => "SELECT DISTINCT c.* , b.role FROM  "
//         . "pro_user as a , pro_user as b , user as c  "
//         . "where a.UID = " . $UID . "  "
//         . "and a.JID = b.JID  "
//         . "and b.UID = c.UID  "
//         . " "
//         . " LIMIT 0, 30 ",
//     ],
// ];

// // echo $a['b'];
// //
// echo $db['pro_work'];
