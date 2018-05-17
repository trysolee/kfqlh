<?php
###############################
# 更改 当前项目.名称
#

include_once "class/cla_project.php";
include_once "class/cla_user.php";
include_once "class/cla_pro_user.php";

SYS::KK('_SESSION', $_SESSION);
$j = cla_project::get当前();

SYS::KK('当前项目.分组', $j->DAT['JSON'][$_SESSION['分组']]);

SYS::KK('JID', $_SESSION['JID']);
SYS::KK('分组', $_SESSION['分组']);

$u = cla_user::getMyself();
SYS::KK('USER', $u->DAT);

cla_pro_user::项目_分组_权限();

SYS::KK('session_id', session_id());
