<?php

include_once 'tools/sys.php';

###############################
# 更改 当前项目.名称
#


SYS::KK('test11', 'io');


// include_once "class/cla_project.php";
// include_once SYS::$filePath['user'];
// include_once "class/cla_pro_user.php";

SYS::KK('_SESSION', $_SESSION);
// $j = cla_project::get当前();

$u = cla_uh::getMyself();
SYS::KK('USER', $u->DAT);

SYS::KK('session_id', session_id());
