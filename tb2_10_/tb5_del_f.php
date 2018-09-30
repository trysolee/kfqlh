<?php
include_once 'tools/sys.php';

## 删除 好友
#

include_once SYS::$filePath['input'];

include_once SYS::$filePath['user'];

$my = cla_user::getMyself();
$my->is管理员_end();

INPUT::参数检查_end([
    ['UID1', 'ID', true], // 自家孩子
    ['UID2', 'ID', true], // 孩子 好友
]);

$UID1 = $_POST['UID1'];
$UID2 = $_POST['UID2'];

$user1 = cla_user::getByID($UID1);
$user1->is同一家庭_end();
$user1->is孩子_end();

$user2 = cla_user::getByID($UID2);

###############################
# 结束返回
#
$RET->toStr_end();
