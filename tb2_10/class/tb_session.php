<?php

include_once SYS::$filePath['val'];

/**
 *
 */
class Session
{

    public static function 已登录_end()
    {
        if (empty($_SESSION["UID"])) {
            $GLOBALS['RET']->错误终止_end('还没登录');
            exit();
        }
    }

    public static function set($user, $openid)
    {
        $ret = $GLOBALS['RET'];
        $ret->setOPT('myJID', $user->家庭ID());
        $ret->toPage('首页');
        $ret->登录返回();

        //

        if (@$_POST['cType'] == 'browser') {
            $_SESSION['showKK'] = true;
        } else {
            $_SESSION['showKK'] = false;
        }

        // 返回 _SIDd
        // 小程序 不自动处理session
        $ret->返回session_id();

        if ($user->isOK()) {
            #
            # 有 user 数据 ( 已经注册 )
            ###############################

            ###############################
            # 记录 UID , day10
            #
            $UID = $_SESSION["UID"] = $user->getUID();

            // LT = 0 ; // 更新全部数据
            $_SESSION["LT"] = 0;
            #
            $_SESSION["is管理员"] = $user->is管理员();
            $_SESSION["家庭ID"]    = $user->家庭ID();
            $_SESSION["JID"]    = $user->家庭ID();

        } else {
            $ret->还没注册();

        }

    }

}
