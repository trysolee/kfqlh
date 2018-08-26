<?php
include_once 'tools/sys.php';

## 添加 好友 _ 申请 邀请码
#

include_once SYS::$filePath['input'];

include_once SYS::$filePath['user'];

include_once SYS::$filePath['invite'];

$我JID = $_SESSION["家庭ID"];

$invite = cla_invite::新建好友邀请($我JID);
$invite->is好友邀请_end();

$RET->setOPT('好友邀请码', $invite->getID());

###############################
# 结束返回
#
$RET->toStr_end();
