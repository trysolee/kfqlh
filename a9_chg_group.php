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

###############################
# 参数检查
#
SYS::参数检查_end(['JID', 'group']);

$u = cla_user::getMyself();
$u->set当前项目分组($_POST['JID'], $_POST['group']);

###############################
# 结束返回
#
$RET->toStr_end();
