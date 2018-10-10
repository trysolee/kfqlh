<?php
include_once 'tools/sys.php';
SYS::$无session_OK = true;

## 注册
#
# 输入 <孩子昵称> <称为> <年级>
#

include_once SYS::$filePath['input'];

include_once SYS::$filePath['openid'];
include_once SYS::$filePath['session'];

include_once SYS::$filePath['u_h'];
include_once SYS::$filePath['u_c'];
include_once SYS::$filePath['家庭'];

INPUT::参数检查_end([
    ['code', 'code', true],
    ['h_NA', '昵称', true], // 孩子昵称
    ['j_NA', '昵称', true], // 家长昵称
    ['LJ', '年级', true], // 年级
]);

$code = $_POST['code'];
$h_NA = $_POST['h_NA'];
$j_NA = $_POST['j_NA'];
$LJ   = $_POST['LJ']; // 暂时没用 ( 年级 )

#####################################
# 获取 openid
#
# 获取 openid失败  自动退出
#
$openCla = cla_openid::getByCode_end($_POST['code']);

#####################################
# 获取'邀请码' 对象
#

if ($openCla->还没注册()) {
    // openid 第一次注册
    //
    // 1 . 创建家庭
    // 2 . 创建<孩子>
    // 3 . 创建<管理员>
    // 4 . 创建<openid>
    //
    $家庭名称 = $h_NA . '的家';
    $家庭       = cla_family::newOne($家庭名称);

    cla_uc::newOne($h_NA, $家庭->getJID());

    $j_NA = $h_NA . $j_NA;
    $user = cla_uh::newOne($j_NA, $家庭->getJID(), '管理员');

    cla_openid::newOne($openCla->getOpenID() //
        , $user->getUID());

    Session::set($user, $openCla);

    RET::toPage('首页');
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
