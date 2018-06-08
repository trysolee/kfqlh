<?php
###############################
# 设置 系统管理员
#

include_once "tools/ret.php";
include_once "tools/sys.php";
include_once "tools/val.php";

###############################
# 权限
#
SYS::is超级管理员_end();

$RET->清空指定BUF('sys_admin');
$a = VAL::get('管理员IDArr');

SYS::KK('$a' , count($a));

if (count($a) > 0) {

    $IDs = '';
    $fg  = '';
#
    foreach ($a as $key => $value) {
        $IDs += $fg + $value;
        $fg = ',';
    }
    $RET->add_SQL([
        'name' => 'sys_admin',

        'sql'  => 'SELECT UID , name'
        . ' FROM  user '
        . ' WHERE UID in (' . $IDs . ')',
    ]);

}
###############################
# 结束返回
#
$RET->toStr_end();
