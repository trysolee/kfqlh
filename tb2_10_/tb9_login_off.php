<?php
include_once 'tools/sys.php';

## 注销账户
#

include_once SYS::$filePath['user'];

$UID  = $_SESSION["UID"];
$user = cla_user::getMyself();

$user->is家长_end();

if ($user->is管理员()) {
    cla_user::del管理员($user->getUID());
} else {
    cla_user::del家长($user->getUID());
}

###############################
# 结束返回
#
$RET->toStr_end();
