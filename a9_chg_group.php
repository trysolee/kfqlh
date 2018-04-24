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

$JID    = $_POST['JID'];
$分组 = $_POST['group'];

$UID = $_SESSION['UID'];
$u   = cla_user::getByID($UID);

if (SYS::is系统管理员()) {
    $u->set当前项目分组($JID, $分组);
    Session::set当前分组($JID, $分组);
    $RET->toStr_end();
    return;
}

cla_pro_user::他是成员_end($JID, $分组, $UID);

$u->set当前项目分组($JID, $分组);
Session::set当前分组($JID, $分组);

###############################
# 结束返回
#
$RET->toStr_end();
