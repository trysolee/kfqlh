<?php
include_once 'tools/sys.php';

## 更新执行包
#

include_once SYS::$filePath['input'];
include_once SYS::$filePath['user'];

INPUT::参数检查_end([
    ['UID', 'ID', true],
    ['JSON', null, true],
]);

$m_box = json_decode($_POST['JSON'], true);

$user = cla_user::getByID($_POST['UID']);
$user->is同一家庭_end();
$user->更新执行包($m_box);

###############################
# 结束返回
#
$RET->toStr_end();
