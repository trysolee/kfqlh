<?php
###############################
# 设置 当前项目.分组
#
# project 的数据不变
# 因为 只是在已授权的几个'项目.分组'里面变更
#

include_once "class/cla_user.php";
include_once "class/cla_project.php";
include_once "class/cla_pro_user.php";
include_once "class/set_session.php";

###############################
# 参数检查
#
SYS::参数检查_end(['JID', 'group']);

$JID    = $_POST['JID'];
$分组 = $_POST['group'];

$UID = $_SESSION['UID'];
$u   = cla_user::getByID($UID);

$RET->换了分组();

if (SYS::is系统管理员()) {

    if ($_SESSION['JID'] != $JID) {
        $RET->换了项目();
    }

    cla_project::存在项目分组_end($JID, $分组);

    $u->set当前项目分组($JID, $分组);
    Session::set当前分组($JID, $分组);
    //

    $RET->toPage('首页');
    $RET->toStr_end();
    return;
}

cla_pro_user::getOne_end($JID, $分组, $UID);

$u->set当前项目分组($JID, $分组);

if ($_SESSION['JID'] != $JID) {
    $RET->换了项目();
}

Session::set当前分组($JID, $分组);


###############################
# 结束返回
#
$RET->toPage('首页');
$RET->toStr_end();
