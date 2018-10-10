<?php
include_once 'tools/sys.php';

## 添加 孩子
#

include_once SYS::$filePath['input'];

include_once SYS::$filePath['u_c'];
include_once SYS::$filePath['u_h'];

cla_uh::我是管理员_end();

INPUT::参数检查_end([
    ['h_NA', '昵称', true],
]);

cla_uc::newOne($_POST['h_NA'], $_SESSION["家庭ID"]);

###############################
# 结束返回
#
RET::ret_buf_min('孩子_重置', 1);
RET::toStr_end();
