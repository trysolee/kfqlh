<?php
include_once 'tools/sys.php';

## 添加 好友
#

include_once SYS::$filePath['input'];

include_once SYS::$filePath['user'];

include_once SYS::$filePath['invite'];

INPUT::参数检查_end([
    ['id', 'ID', true], // 邀请码ID
]);

$我JID = $_SESSION["家庭ID"];

$ID     = $_POST['id'];
$邀请 = cla_invite::getByID_end($ID);
$邀请->is好友邀请_end();
$友JID = $邀请->getJID();

SDB::exec('INSERT IGNORE INTO'
    . ' `tb2_friend` ( `JID` , `UID` )'
    . ' SELECT ' . $我JID . ', UID'
    . ' FROM tb2_user'
    . ' where JID = ' . $友JID
);

SDB::exec('INSERT IGNORE INTO'
    . ' `tb2_friend` ( `JID` , `UID` )'
    . ' SELECT ' . $友JID . ', UID'
    . ' FROM tb2_user'
    . ' where JID = ' . $我JID
);

###############################
# 结束返回
#
$RET->toStr_end();
