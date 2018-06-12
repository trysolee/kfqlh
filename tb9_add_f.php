<?php
include_once 'tools/sys.php';

## 添加 好友
#

include_once SYS::$filePath['input'];

include_once SYS::$filePath['user'];

INPUT::参数检查_end([
    ['UID1', 'ID', true], // 自家孩子
    ['UID2', 'ID', true], // 孩子 好友
]);

$UID1 = $_POST['UID1'];
$UID2 = $_POST['UID2'];

$user = cla_user::getByID($UID1);
$user->is同一家庭_end();
$user->is孩子_end();

cla_user::添加好友($UID1, $UID2);

###############################
# 结束返回
#
$RET->toStr_end();
