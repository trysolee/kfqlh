<?php
include_once 'tools/sys.php';

## 添加 孩子
#

include_once SYS::$filePath['input'];

include_once SYS::$filePath['user'];

$my = cla_user::getMyself();
$my->is管理员_end();

INPUT::参数检查_end([
    ['h_NA', '昵称', true],
]);

cla_user::newOne($_POST['h_NA'], $_SESSION["家庭ID"], '孩子');

###############################
# 结束返回
#
$RET->toStr_end();
