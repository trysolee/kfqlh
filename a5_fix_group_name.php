<?php
###############################
# 更改 当前项目.分组.名称
#

include_once "class/cla_project.php";
include_once "class/cla_pro_user.php";

###############################
# 参数检查
#
SYS::参数检查_end(['name']);

cla_pro_user::我是分组管理员_end();

$j = cla_project::get当前();

$j->fix分组名($_POST['name']);

###############################
# 结束返回
#
$RET->toStr_end();
