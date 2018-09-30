<?php
include_once 'tools/sys.php';

## 添加 家长 _ 申请 邀请码
#

include_once SYS::$filePath['input'];

include_once SYS::$filePath['user'];

include_once SYS::$filePath['invite'];

$我JID = $_SESSION["家庭ID"];

$invite = cla_invite::新建家长邀请($我JID);
$RET->setOPT('家长邀请码', $invite->getID());

###############################
# 结束返回
#
$RET->toStr_end();
