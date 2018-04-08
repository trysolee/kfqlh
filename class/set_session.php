<?php

include_once "/tools/sys.php";

/**
 *
 */
class Session
{

    public static function set($user, $openid)
    {

        if ($user->OK) {
            #
            # 有 user 数据 ( 已经注册 )
            ###############################

            ###############################
            # 记录 UID , day10
            #
            $UID = $_SESSION["UID"] = $user->getUID();
            $_SESSION["LT"] = $user->getLT();
            #
            $_SESSION['day10'] = date('Y-m-d', strtotime('-10 day'));

            ###############################
            # 记录 当前项目ID , 当前分组
            #
            $JID = $_SESSION["JID"] = $user->get当前项目ID();
            #
            $_SESSION["分组"] = $user->get当前分组();
            #
            $J                = cla_project::getByID($JID);
            $_SESSION["role"] = $J->getRole();
            #

            ###############################
            # '超级管理员'
            #
            if ($openid == SYS::$adminOpenID) {
                $_SESSION['supAdmin'] = true;
            }

            ###############################
            # '系统管理员'
            #
            if (in_array($UID, SYS::getJSON('管理员IDArr'))) {
                $_SESSION['Admin'] = true;
            }

        } else {
            $GLOBALS['RET']->还没注册();

        }

    }

}
