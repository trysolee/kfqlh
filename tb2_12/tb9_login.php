<?php
include_once 'tools/sys.php';
SYS::$无session_OK = true;

# 通过微信登录
#
# 1 . 正常登录
#
# 2 . openid 还未注册
#
# 3 . code 无效
#

include_once SYS::$filePath['input'];

include_once SYS::$filePath['openid'];

include_once SYS::$filePath['session'];

include_once SYS::$filePath['u_h'];

INPUT::参数检查_end([
    ['code', 'code', true],
]);

#####################################
# 获取 openid
#
# 获取 openid失败  自动退出
#
$openCla = cla_openid::getByCode_end($_POST['code']);

if ($openCla->还没注册()) {
    RET::还没注册_end();
}

$u = cla_uh::getByID($openCla->getUID());
Session::set($u, $openCla);

###############################
# 结束返回
#
// 在  Session::set() 里面执行
//
// RET::setOPT('myJID', $user->家庭ID());
// RET::toPage('首页');
// RET::登录返回();
RET::ret_buf_min('家庭_重置' , 1);
RET::ret_buf_min('自己_重置' , 1);
RET::ret_buf_min('家长_重置' , 1);
RET::ret_buf_min('孩子_重置' , 1);
RET::toStr_end();
