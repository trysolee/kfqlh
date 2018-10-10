<?php
include_once 'tools/sys.php';

include_once SYS::$filePath['session'];
include_once SYS::$filePath['input'];
include_once SYS::$filePath['u_h'];

INPUT::参数检查_end([
    ['UID', 'ID', true],
    ['m', '短密', true], // m : 变化值
]);

cla_uh::我是管理员_end();

$user = cla_uh::getByID($_POST['UID']);
$user->is同一家庭_end();
$user->修改密码($_POST['m']);

// RET::登录返回();
RET::ret_buf_min('家长_更新' , 1);
RET::toStr_end();
