<?php
include_once 'tools/sys.php';

## 注销 孩子 家长
#

include_once SYS::$filePath['input'];

include_once SYS::$filePath['user'];

INPUT::参数检查_end([
    ['UID', 'ID', true],
]);

cla_user::我是管理员_end();

$UID = $_POST['UID'];

$user = cla_user::getByID($UID);
$user->is同一家庭_end();
// $user->is孩子_end();

$user->注销();

###############################
# 结束返回
#
$RET->toStr_end();
