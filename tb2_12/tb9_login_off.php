<?php
include_once 'tools/sys.php';

## 注销账户
# <家长.注销自己>
#

include_once SYS::$filePath['u_h'];

$UID  = $_SESSION["UID"];
$user = cla_uh::getMyself();

if ($user->is管理员()) {
    RET::错误终止_end('不能注销管理员');
} else {
    $user->注销();
}

###############################
# 结束返回
#
// RET::返回后续('还没注册');
//
RET::toPage('注册'); // 不用显示<操作指引>
RET::toStr_end(); // 有<save>操作
//
// RET::还没注册_end();
