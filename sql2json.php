<?php

// 设置返回json格式数据
header('content-type:application/json;charset=utf-8');

//连接数据库
$link = mysql_connect("localhost", "root", "root") or die("Unable to connect to the MySQL!");

mysql_query("SET NAMES 'UTF8'");

mysql_select_db("kfqlh", $link) or die("Unable to connect to the MySQL!");

// 查询数据到数组中
// $result = mysql_query("select UID ,name ,phone  from user ");
// $result = mysql_query("select *  from pro_work ");
//

// mysqli_query("UPDATE projoct set  jname ='恭喜发财' where JID = 1 ");

mysql_query('UPDATE projoct SET jname="恭喜发财" WHERE JID="1" ');

$result = mysql_query("select *  from projoct ");

$results = array();
while ($row = mysql_fetch_assoc($result)) {

    // $row['JSON'] = json_decode($row['JSON'], true);
    // $txt = json_decode($row->txt, true)

    $results[] = $row;
}

// 将数组转成json格式
echo json_encode($results, JSON_UNESCAPED_UNICODE);

// 关闭连接
mysql_free_result($result);

mysql_close($link);

echo '万册3两个';
