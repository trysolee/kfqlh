<?php
include_once 'tools/sys.php';

include_once SYS::$filePath['session'];
include_once SYS::$filePath['input'];
include_once SYS::$filePath['user'];

INPUT::参数检查_end([
    ['UID', 'ID', true],
    ['m', '短密', true], // m : 变化值
]);

$user = cla_user::getByID($_POST['UID']);
$user->is同一家庭_end();
$user->修改密码($_POST['m']);

// $RET->登录返回();
$RET->toStr_end();
