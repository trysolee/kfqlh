<?php
###############################
# 设置 系统管理员
#

include_once "tools/ret.php";
include_once "tools/sys.php";
include_once "tools/val.php";

###############################
# 权限
#
SYS::is超级管理员_end();

###############################
# 参数检查
#
SYS::参数检查_end(['ARR']);

$UIDs = json_decode($_POST['ARR'], true);

VAL::set('管理员IDArr', array_unique($UIDs ));

###############################
# 结束返回
#
$RET->toStr_end();
