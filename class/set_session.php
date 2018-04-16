<?php

include_once "/tools/sys.php";
include_once "/tools/val.php";

/**
 *
 */
class Session
{

    public static function set当前分组($JID, $分组)
    {
        $_SESSION["JID"]    = $JID;
        $_SESSION["分组"] = $分组;

    }

    public static function set($user, $openid)
    {
        unset($_SESSION['supAdmin']);
        unset($_SESSION['Admin']);

        // 如果 是 系统管理员 , 不会读取项目的权限
        $_SESSION["role"] = [];

        if ($user->isOK()) {
            #
            # 有 user 数据 ( 已经注册 )
            ###############################

            ###############################
            # 记录 UID , day10
            #
            $UID            = $_SESSION["UID"]            = $user->getUID();
            $_SESSION["LT"] = $user->getLT();
            #
            $_SESSION['day10'] = date('Y-m-d', strtotime('-10 day'));

            ###############################
            # 记录 当前项目ID , 当前分组
            #
            $JID = $_SESSION["JID"] = $user->get当前项目ID();

            ###############################
            # 保证第一次返回数据 ,
            # 是以 全新项目去 缓存
            #
            $_SESSION['JID_LT'] = -1;

            #
            $_SESSION["分组"] = $user->get当前分组();

            ###############################
            # '超级管理员'
            #
            if ($openid == SYS::$adminOpenID) {
                $_SESSION['supAdmin'] = true;
            }

            ###############################
            # '系统管理员'
            #
            elseif (in_array($UID, VAL::get('管理员IDArr'))) {
                $_SESSION['Admin'] = true;

            } else {
                $J                = cla_project::getByID($JID);
                $_SESSION["role"] = $J->getRole();
            }

        } else {
            $GLOBALS['RET']->还没注册();

        }

    }

}
