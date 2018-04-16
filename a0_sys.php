<?php
###############################
# 更改 当前项目.名称
#

include_once "/tools/ret.php";
include_once "/tools/sys.php";
include_once "/class/cla_project.php";
include_once "/class/cla_user.php";

SYS::KK('_SESSION', $_SESSION);
$j = cla_project::get当前();

SYS::KK('当前项目.分组', $j->DAT['JSON'][$_SESSION['分组']]);

$a = [];
foreach ($j->DAT['JSON'][$_SESSION['分组']]['user'] as $key => $value) {
    $a[$key] = $value['role'];
}
SYS::KK(' 项目.分组权限 ', $a);

SYS::KK('JID', $_SESSION['JID']);
SYS::KK('分组', $_SESSION['分组']);

$u = cla_user::getMyself();
SYS::KK('USER', $u->DAT);
