<?php
include_once 'tools/sys.php';

## 注销 家长
# <管理员.操作> 注销其他家长
#

include_once SYS::$filePath['input'];

include_once SYS::$filePath['u_h'];

INPUT::参数检查_end([
    ['UID', 'ID', true],
]);

cla_uh::我是管理员_end();

$UID = $_POST['UID'];

$user = cla_uh::getByID($UID);
$user->is同一家庭_end();

if ($user->is管理员()) {
    RET::错误终止_end('不能注销管理员');
}

$user->注销();

###############################
# 结束返回
#
RET::ret_buf('家长_重置');
RET::toStr_end();
// RET::还没注册_end();
