<?php

## 通过邀请码注册
#
# 1 . 第一次扫描注册
# openid 第一次注册
#
# 2 . 第二次扫描注册
# openid 已经注册过
# 同时 属于多个'分组'
#
# 3 . code 解析错误
#
# 4 . in 邀请码错误
#
# 5 . 初始化'超级管理员'
#

include_once "/tools/ret.php";
include_once "/tools/sys.php";
include_once "/tools/begin.php";

include_once "/class/cla_openid.php";
include_once "/class/cla_in.php";
include_once "/class/set_session.php";

SYS::参数检查_end(['code', 'in', 'username']);

#####################################
# 获取 openid
#
# 获取 openid失败  自动退出
#
$openCla = cla_openid::getByCode_end($_POST['code']);

#####################################
# 系统初始化 , 不用管 '邀请码'
#

if ($openCla->is超级()) {

    if ($_POST['in'] == SYS::$初始化码) {

        BEGIN::新建超级管理员($_POST['username']);
        return;
    }

    if ($_POST['in'] == SYS::$清除数据) {

        BEGIN::清空数据();
        return;
    }

}

#####################################
# 获取'邀请码' 对象
#
$IN = cla_in::getByIN_end($_POST['in']);

if ($openCla->还没注册()) {
    $IN->首次邀请($openCla, $_POST['username']);
} else {
    $IN->二次邀请($openCla);
}

###############################
# 结束返回
#
$RET->登录返回();
$RET->toStr_end();
