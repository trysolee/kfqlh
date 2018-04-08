<?php

include_once '/tools/sdb.php';
include_once "/tools/ret.php";
/**
 *
 */
class SYS
{
    ##############################
    # 超级管理员
    # 1 . 拥有 '系统管理员' 的权限
    # 2 . 新建 project (项目)
    # 3 . 设定 '系统管理员'
    #
    public static $adminOpenID = '';

    ##############################
    #
    public static $项目名_长度 = 35;

    ##############################
    #
    public static $分组名_长度 = 35;

    ##############################
    # 注册变量 列表
    #
    # '管理员IDArr'
    #
    public static $varNameArr = [

        ##############################
        # 系统管理员 ( 管理员IDArr )
        # 1 . 拥有'分组.管理员' 的权限
        # 2 . 以任何'分组.管理员' 登录
        #
        '管理员IDArr',

    ];

    public static function getJSON($n)
    {
        $sql = "SELECT JSON  FROM val  where name = '" . $n . "'";

        $d = SDB::SQL($sql);

        if (SDB::$notFind) {
            $GLOBALS['RET']->错误终止_end('val 无效');
            exit();
        }
        return $d['JSON'];

    }

    public static function setJSON($n, $v)
    {
        $d = [
            'name'  => 'val',
            'DAT'   => [
                'JSON' => $v,
            ],
            'WHERE' => [
                'name' => $n,
            ],
        ];

        SDB::update($d);
    }

    public static function is超级管理员_end()
    {
        if (!SYS::is超级管理员()) {
            $GLOBALS['RET']->不是管理员_end();
            exit();
        }
    }

    public static function is超级管理员()
    {
        if (empty($_SESSION['supAdmin'])) {
            return false;
        } else {
            return true;
        }
    }

    public static function is系统管理员()
    {
        #########################
        # 如果是 超级管理员 , 也 返回true;
        #
        if (SYS::is超级管理员()) {
            return true;
        }

        if (empty($_SESSION['Admin'])) {
            return false;
        } else {
            return true;
        }
    }
    
    ##############################
    #  增加 一个系统管理员
    #
    public static function add_管理员($UID)
    {
        $a   = SYS::getJSON('管理员IDArr');
        $a[] = $UID;

        SYS::setJSON('管理员IDArr', $a);
    }

    ##############################
    #  移去一个 系统管理员
    #
    public static function remove_管理员($UID)
    {
        $a = SYS::getJSON('管理员IDArr');

        SYS::setJSON('管理员IDArr',
            array_diff($a, [$UID]));

    }

    ##############################
    #
    public static function 参数检查_end($arr)
    {
        foreach ($arr as $value) {
            if (empty($_POST[$value])) {
                $GLOBALS['RET']->参数不全_end();
                exit();
            }
        }
    }
}
