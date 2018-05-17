<?php
###############################
# 移去一个 系统管理员
#

include_once "tools/ret.php";
include_once "tools/sys.php";
include_once "class/cla_project.php";

###############################
# 权限
#
SYS::is超级管理员_end();

###############################
# 参数检查
#
SYS::参数检查_end(['UID']);

SYS::remove_管理员($_POST['UID']);

###############################
# 结束返回
#
$RET->toStr_end();
