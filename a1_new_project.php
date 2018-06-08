<?php

## 新建 project ( 超级管理员 权限 )
#

include_once "tools/ret.php";
include_once "tools/sys.php";
include_once "class/cla_project.php";
include_once "class/cla_pro_user.php";
include_once 'class/cla_user.php';

###############################
# 参数检查
#
SYS::参数检查_end(['pro_name']);

###############################
# 是否 '超级管理员'
#
SYS::is超级管理员_end();

###############################
# 新建项目
#
$j = cla_project::newProject($_POST['pro_name']);
$u = cla_user::getMyself();
cla_pro_user::putOne($_SESSION["UID"]//
    , $u->getUserName() //
    , $j->getJID() //
    , '临时' //
    , $_SESSION["UID"]//
);

###############################
# 结束返回
#
$RET->toStr_end();
