<?php
include_once 'tools/sys.php';

## 改名 , ( 孩子 / 家长 )
#

include_once SYS::$filePath['input'];

include_once SYS::$filePath['user'];

$my = cla_user::getMyself();
$my->is管理员_end();

INPUT::参数检查_end([
    ['UID', 'ID', true],
    ['NA', '昵称', true],
]);

$UID = $_POST['UID'];
$NA  = $_POST['NA'];

$user = cla_user::getByID($UID);
$user->is同一家庭_end();

$user->改名($NA);

###############################
# 结束返回
#
$RET->toStr_end();
