<?php
include_once 'tools/sys.php';

include_once SYS::$filePath['session'];
include_once SYS::$filePath['input'];
include_once SYS::$filePath['u_c'];

INPUT::参数检查_end([
    ['UID', 'ID', true],
    ['m', '数值', true], // m : 变化值
]);

$user = cla_uc::getByID($_POST['UID']);
$user->is同一家庭_end();
$user->更新存款($_POST['m']);

// RET::登录返回();
RET::ret_buf_min('孩子_更新' , 1);
RET::toStr_end();
