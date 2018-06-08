<?php
########################################
# 返回 <分组>的全部 <user>

include_once "tools/sdb.php";
include_once "class/cla_project.php";
include_once "class/cla_pro_user.php";

SDB::bSet();

cla_pro_user::我是分组管理员_end();

###############################
# 结束返回
#
$RET->清空指定BUF('pro_all_user');
$RET->返回pro的全部user();
$RET->toStr_end();
