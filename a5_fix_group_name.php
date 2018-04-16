<?php
###############################
# 更改 当前项目.分组.名称
#

include_once "/tools/ret.php";
include_once "/tools/sys.php";
include_once "/class/cla_project.php";

###############################
# 参数检查
#
SYS::参数检查_end(['name']);

$j = cla_project::get当前();

###############################
# 权限
#
$j->我是管理员_end();

$j->fix分组名($_POST['name']);

###############################
# 结束返回
#
$RET->toStr_end();

