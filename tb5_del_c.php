<?php
include_once 'tools/sys.php';

## 注销 孩子
#

include_once SYS::$filePath['input'];

include_once SYS::$filePath['user'];

INPUT::参数检查_end([
    ['UID', 'ID', true],
]);

$UID = $_POST['UID'];

$user = cla_user::getByID($UID);
$user->is同一家庭_end();
$user->is孩子_end();

cla_user::del孩子($UID);

###############################
# 结束返回
#
$RET->toStr_end();
