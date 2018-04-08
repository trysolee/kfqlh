<?php

// 设置返回json格式数据
header('content-type:application/json;charset=utf8');
session_start();



include_once "getdb.php";
include_once "ret.php";




$c = $_GET['code'];


$appID  = 'wxfa85e735b154f325';
$secret = '4220c26ad926c84fb4fbaf0e8b8217fb';


$url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . $appID . "&secret=" . $secret . "&js_code=" . $c . "&grant_type=authorization_code";


$weixin = file_get_contents($url); //通过code换取网页授权access_token

$array = json_decode($weixin, true);

$openid = $array['openid']; //输出openid

$openid = 123456;

// ========================================================
//
$R = new ret();

$D    = new getDB();
$user = $D->UserByOpenID($openid);

if ($D->notFind) {

    if (empty($_GET['in']) || empty($_GET['username'])) {
        // ================================================  openid 无效  ,也没有 '邀请码'
        $R->setOPT('login', 'ERR');
        $R->setOPT('IN', 'no');

    } else {
        # 登录不成功
        # 有 "邀请码"

        $pro = $D->proByIN($_GET['in']);

        if ($D->notFind) {
            // ================================================  邀请码 无效
            $R->setOPT('login', 'ERR');
            $R->setOPT('IN', 'ERR');

        } else {
            // =========================================== 邀请码 有效
           
$t = strtotime('now');

            $d = [
                'name' => 'user',
                'DAT'  => [
                    // 'JID'   => 1,
                    'JSON' => [
                        'name'     => $_GET['username'],
                        'role'     => [
                            $pro['JID'] => $pro['JSON']['role'],
                        ],
                        'proID'    => $pro['JID'],
                        'inUserID' => $pro['UID'],
                    ],
                    'FT'   => $t,
                    'LT'   => $t,

                ],
            ];
            $D->insert($d);
        }

    }


} else {

    /// ======================================================= 正常登录

    $R->setOPT('login', 'OK');

    $_SESSION["user"] = $user;

    //
    $userJson = $user['JSON'];
    if (empty($userJson['inUserID'])) {

        $userJson['inUserID'] = array_keys($userJson['role'])[0];
    }

    $_SESSION["role"] = $userJson['role'][$userJson['inUserID']]; // 设置当前项目 的role

    $_SESSION['day10'] = date('Y-m-d', strtotime('-10 day'));

// ========================
    //

    $R->getDAT();
}

$R->toStr();
