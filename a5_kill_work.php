<?php
###############################
# 屏蔽 一个 帖子
# ( 管理员)
#
# 不会被看到
#

include_once "/tools/sys.php";
include_once "/tools/ret.php";
include_once "/class/cla_work.php";

###############################
# 参数检查
#
SYS::参数检查_end(['WID']);

cla_pro_user::我是分组管理员_end();

$w = cla_work::getByID($_POST['WID']);
$w->is当前项目_分组_end();

$w->killIt();

###############################
# 结束返回
#
$RET->toStr_end();
