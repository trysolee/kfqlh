<?php
include_once 'tools/sys.php';

## 注册
#
# 输入 <孩子昵称> <称为> <年级>
#

include_once SYS::$filePath['input'];

include_once SYS::$filePath['openid'];
include_once SYS::$filePath['session'];

include_once SYS::$filePath['user'];
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
# 系统初始化 , 不用管 '邀请码'
#
// if ($openCla->is超级()) {

//     if ($_POST['in'] == SYS::$初始化码) {

//         BEGIN::新建超级管理员($_POST['username']);
//         SYS::KK('新建超级管理员ok', ' ');

//         $RET->登录返回();
//         $RET->toStr_end();
//         return;
//     }

//     if ($_POST['in'] == SYS::$清除数据) {

//         BEGIN::清空数据();

//         SYS::KK('清空数据ok', ' ');
//         return;
//     }

// }

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

    cla_user::newOne($h_NA, $家庭->getJID(), '孩子');

    $j_NA = $h_NA . $j_NA;
    $user = cla_user::newOne($j_NA, $家庭->getJID(), '管理员');

    cla_openid::newOne($openCla->getOpenID() //
        , $user->getUID());

    Session::set($user, $openCla);

    $RET->toPage('首页');
    $RET->登录返回();
} else {
    $GLOBALS['RET']->错误终止_end('需要先注销账户');
}

###############################
# 结束返回
#
$RET->toStr_end();
