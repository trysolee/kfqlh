<?php
include_once 'tools/sys.php';

## 注销 孩子
#

include_once SYS::$filePath['input'];

include_once SYS::$filePath['u_c'];
include_once SYS::$filePath['u_h'];

INPUT::参数检查_end([
    ['UID', 'ID', true],
]);

cla_uh::我是管理员_end();

$UID = $_POST['UID'];

$user = cla_uc::getByID($UID);
$user->is同一家庭_end();

$user->注销();

###############################
# 结束返回
#
RET::ret_buf('孩子_重置');
RET::toStr_end();
