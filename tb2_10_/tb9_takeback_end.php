<?php
// 结束任务

include_once 'tools/sys.php';

include_once SYS::$filePath['session'];
include_once SYS::$filePath['input'];
include_once SYS::$filePath['user'];

INPUT::参数检查_end([
    ['UID', 'ID', true],
    ['T', '数值', true], // m : 变化值
]);

$user = cla_user::getByID($_POST['UID']);
$user->is同一家庭_end();
$user->更新存款(-1 * $_POST['T']);
$user->任务结束();
//

// $RET->登录返回();
$RET->返回后续('提款结束');
$RET->toStr_end();
