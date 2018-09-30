<?php
include_once 'tools/sys.php';

## 注册 旧家庭
#
# 输入
# <称为>
# <邀请码>
#

include_once SYS::$filePath['input'];

include_once SYS::$filePath['openid'];
include_once SYS::$filePath['session'];

include_once SYS::$filePath['user'];
include_once SYS::$filePath['invite'];
// include_once SYS::$filePath['家庭'];

INPUT::参数检查_end([
    ['code', 'code', true],
    // ['h_NA', '昵称', true], // 孩子昵称
    ['j_NA', '昵称', true], // 家长称为
    // ['LJ', '年级', true], // 年级
    ['invite', 'ID', true], //
]);

$code = $_POST['code'];
// $h_NA = $_POST['h_NA'];
$j_NA = $_POST['j_NA'];
// $LJ   = $_POST['LJ']; // 暂时没用 ( 年级 )
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

    $user = cla_user::newOne($j_NA, $邀请->getJID(), '家长');

    cla_openid::newOne($openCla->getOpenID() //
        , $user->getUID());

    Session::set($user, $openCla);

    // 在  Session::set() 里面执行
    // 
    // $RET->setOPT('myJID', $user->家庭ID());
    // $RET->toPage('首页');
    // $RET->登录返回();

} else {

    $GLOBALS['RET']->错误终止_end('需要先注销账户');
}

###############################
# 结束返回
#

$RET->toStr_end();
