<?php
###############################
# 设置 当前项目.分组
#
# project 的数据不变
# 因为 只是在已授权的几个'项目.分组'里面变更
#

include_once "/tools/sys.php";
include_once "/tools/ret.php";
include_once "/class/cla_user.php";
include_once "/class/cla_work.php";

###############################
# 参数检查
#
SYS::参数检查_end(['WID', 'ARR']);

$WID = $_POST['WID'];
$ARR = json_decode($_POST['ARR'], true);

$w = cla_work::getByID($WID);
$w->is我发布的or分组管理员_end();

// SYS::KK('work DAT', $w->DAT);

cla_project::标签合法_end($w->get分组(), $ARR);

$w->设置标签($ARR);

###############################
# 结束返回
#
$RET->toStr_end();
