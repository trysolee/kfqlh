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

    public static function set当前byUser_end($u)
    {

        if (empty($u->DAT['JSON']['JID'])) {
            // 没有 当前项目
            $o = cla_pro_user::get任一个($u->getUID());
            $u->set当前byPro_User($o);
            if (empty($u->DAT['JSON']['JID'])) {
                // 还是 没有 当前项目
                $GLOBALS['RET']->不在项目_end();
            }
        }
        $_SESSION["JID"]    = $u->get当前项目ID();
        $_SESSION["分组"] = $u->get当前分组();

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
            $UID = $_SESSION["UID"] = $user->getUID();

            $_SESSION["LT"] = $user->getLT();
            #
            $_SESSION['day10'] = date('Y-m-d', strtotime('-10 day'));

            ###############################
            # 记录 当前项目ID , 当前分组
            #
            Session::set当前byUser_end($user);
            $JID    = $_SESSION["JID"];
            $分组 = $_SESSION["分组"];

            ###############################
            # 保证第一次返回数据 ,
            # 是以 全新项目去 缓存
            #
            $_SESSION['JID_LT'] = -1;

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

                SYS::KK('非管理员', null);
                $_SESSION["role"] = cla_pro_user::getRole($JID, $分组, $UID);
            }

        } else {
            $GLOBALS['RET']->还没注册();

        }

    }

}
