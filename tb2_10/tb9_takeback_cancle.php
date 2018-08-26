<?php
// 取消任务

include_once 'tools/sys.php';

include_once SYS::$filePath['session'];
include_once SYS::$filePath['input'];
include_once SYS::$filePath['user'];

INPUT::参数检查_end([
    ['UID', 'ID', true],
]);

$user = cla_user::getByID($_POST['UID']);
$user->is同一家庭_end();
$user->任务结束();
//

// $RET->登录返回();
$RET->toStr_end();
