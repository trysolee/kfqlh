<?php
###############################
# 管理员 修改 project.分组 的人员权限
#
# 1 . 可以把成员 '踢走'
#
# 2 . 不能把人拉进来 , 只能邀请
#
include_once "class/cla_project.php";
include_once "class/cla_pro_user.php";

###############################
# 参数检查
#
SYS::参数检查_end(['ARR']);

cla_pro_user::我是分组管理员_end();

$UIDs = json_decode($_POST['ARR'], true);

$UID = $_SESSION['UID'];
$JID = $_SESSION['JID'];
$分组 = $_SESSION['分组'];
foreach ($UIDs as $key => $value) {

    // 设定自己时 , 一定要加入 '管理员' 权限
    // 避免 整个分组都没有 '管理员'
    if ($key == $UID) {
        $value[] = '管理员';
        $value   = array_unique($value);
    }

    cla_pro_user::setRole(
        $JID, $分组, $key, $value
    );

}

###############################
# 结束返回
#
$RET->toStr_end();
