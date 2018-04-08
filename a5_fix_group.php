<?php
###############################
# 管理员 修改 project.分组 的人员权限
#
# 1 . 可以把成员 '踢走'
#
# 2 . 不能把人拉进来 , 只能邀请
#
include_once "/tools/sys.php";
include_once "/tools/ret.php";
include_once "/class/cla_project.php";

###############################
# 参数检查
#
SYS::参数检查_end(['ARR']);

$j = cla_project::get当前();
$j->我是管理员_end();

$UIDs = json_decode($_POST['ARR'], true);

foreach ($UIDs as $key => $value) {
    $j->fix某人权限($key, $value);
}

###############################
# 结束返回
#
$RET->toStr_end();
