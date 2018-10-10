<?php
include_once 'tools/sys.php';
SYS::$无session_OK = true;

## 注册 旧家庭
#
# 输入
# <称为>
# <邀请码>
#

include_once SYS::$filePath['input'];

include_once SYS::$filePath['openid'];
include_once SYS::$filePath['session'];

include_once SYS::$filePath['u_h'];
include_once SYS::$filePath['u_c'];
include_once SYS::$filePath['invite'];
// include_once SYS::$filePath['家庭'];

INPUT::参数检查_end([
    ['code', 'code', true],
    ['j_NA', '昵称', true], // 家长称为
    ['invite', 'ID', true], //
]);

$code = $_POST['code'];
$j_NA = $_POST['j_NA'];
$ID = $_POST['invite']; //

#####################################
# 获取 openid
#
# 获取 openid失败  自动退出
#
$openCla = cla_openid::getByCode_end($code);

$邀请 = cla_invite::getByID_end($ID);
$邀请->is家长邀请_end();

#####################################
# 获取'邀请码' 对象
#

if ($openCla->还没注册()) {
    // openid 第一次注册
    //
    //

    $user = cla_uh::newOne($j_NA, $邀请->getJID(), '家长');

    cla_openid::newOne($openCla->getOpenID() //
        , $user->getUID());

    Session::set($user, $openCla);

    // 在  Session::set() 里面执行
    // 
    // RET::setOPT('myJID', $user->家庭ID());
    // RET::toPage('首页');
    // RET::登录返回();

} else {

    RET::错误终止_end('需要先注销账户');
}

###############################
# 结束返回
#
RET::ret_buf_min('家庭_重置' , 1);
RET::ret_buf_min('自己_重置' , 1);
RET::ret_buf_min('家长_重置' , 1);
RET::ret_buf_min('孩子_重置' , 1);
RET::toStr_end();
