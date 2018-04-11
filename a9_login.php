<?php

// date_default_timezone_set("PRC");

# 通过微信登录
#
# 1 . 正常登录
#
# 2 . openid 还未注册
#
# 3 . code 无效
#

include_once "tools/ret.php";
include_once "tools/sys.php";
include_once "class/set_session.php";

SYS::参数检查(['code']);

#####################################
# 获取 openid
#
# 获取 openid失败  自动退出
#
$openCla = cla_openid::getByCode_end($_POST['code']);

$u = $openCla->getUser();
Session::set($u, $openCla->getOpenID());

###############################
# 结束返回
#
$RET->登录返回();
$RET->toStr_end();
