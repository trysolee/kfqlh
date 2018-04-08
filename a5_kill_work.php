<?php
###############################
# 关闭 一个 帖子
# ( 自己的帖子 或 管理员)
#

include_once "/tools/sys.php";
include_once "/tools/ret.php";
include_once "/class/cla_work.php";

###############################
# 参数检查
#
SYS::参数检查_end(['WID']);

$w = cla_work::getByID($_POST['WID']);
$w->is分组管理员_end();

$w->killIt();

###############################
# 结束返回
#
$RET->toStr_end();
