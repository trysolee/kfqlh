<?php
include_once 'tools/sys.php';

## 添加 家长
#

include_once SYS::$filePath['input'];

include_once SYS::$filePath['openid'];
include_once SYS::$filePath['user'];
include_once SYS::$filePath['session'];

INPUT::参数检查_end([
    ['code', 'code', true],
    ['JID', 'ID', true], // 自家孩子
    ['j_na', '昵称', true], // 孩子 好友
]);

#####################################
# 获取 openid
#
# 获取 openid失败  自动退出
#
$openCla = cla_openid::getByCode_end($_POST['code']);

if ($openCla->还没注册()) {
    // openid 第一次注册
    //
    // 1 . 创建<user>
    // 2 . 创建<openid>
    //

    $JID  = $_POST['JID'];
    $j_na = $_POST['j_na'];

    $user = cla_user::newOne($j_na, $JID, '家长');

    cla_openid::newOne($openCla->getOpenID() //
        , $user->getUID());

} else {
    $GLOBALS['RET']->错误终止_end('需要先[注销]账户');
}

###############################
# 结束返回
#
$RET->登录返回();
$RET->toStr_end();
