<?php
include_once 'tools/sys.php';

## 改名 , ( 家长 )
#

include_once SYS::$filePath['input'];

include_once SYS::$filePath['u_h'];

cla_uh::我是管理员_end();

INPUT::参数检查_end([
    ['UID', 'ID', true],
    ['NA', '昵称', true],
]);

$UID = $_POST['UID'];
$NA  = $_POST['NA'];

$user = cla_uh::getByID($UID);
$user->is同一家庭_end();

$user->改名($NA);

###############################
# 结束返回
#
RET::ret_buf_min('家长_更新' , 1);
RET::toStr_end();
