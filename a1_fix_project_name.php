<?php
###############################
# 更改 当前项目.名称
#

include_once "/tools/ret.php";
include_once "/tools/sys.php";
include_once "/class/cla_project.php";

###############################
# 权限
#
SYS::is超级管理员_end();

###############################
# 参数检查
#
SYS::参数检查_end(['name']);

$j = cla_project::get当前();
$j->fix项目名($_POST['name']);

###############################
# 结束返回
#
$RET->toStr_end();
