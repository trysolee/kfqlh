<?php

include_once 'tools/sdb.php';
include_once "tools/ret.php";
include_once "tools/val.php";
/**
 *
 */
class SYS
{

    ##############################
    # 调试
    #
    public static $调试 = false;
    //
    public static $openid不解析 = true;
    // public static $openid不解析 = false;
    //
    // public static $打开KK = true;
    public static $打开KK = false;
    ##############################
    //
    public static $DBNL = [
        'val'    => 'tb2_val',
        'openid' => 'openid',
        'user'   => 'tb2_user',
        'friend' => 'tb2_friend',
        'family' => 'tb2_family',
        'invite' => 'tb2_invite',
    ];

    public static $filePath = [

        'sdb'        => 'tools/sdb.php',
        'sdb_one'    => 'tools/sdb_one.php',
        'get_openid' => 'class/get_openid.php',
        'openid'     => 'class/cla_openid.php',
        'begin'      => 'tools/begin.php',
        'val'        => 'tools/val.php',
        'session'    => 'class/tb_session.php',
        'input'      => 'tools/input.php',
        'invite'     => 'class/cla_invite.php',

        'user'       => 'class/tb_user.php',
        '家庭'         => 'class/cla_family.php',
    ];

    public static $无session_OK = false;

    public static function if没测试_end()
    {
        if (SYS::$调试) {
            return;
        }
        //
        $GLOBALS['RET']->toPage('首页');
        $GLOBALS['RET']->错误终止_end('没在测试');
    }

    public static function KK($n, $v)
    {
        if (!SYS::$打开KK) {
            return;
        }

        echo "[ $n ]";

        if ($v == ' ') {

        } elseif (is_array($v)) {
            echo ('::<br/>');
            print_r(json_encode($v, JSON_UNESCAPED_UNICODE));
            //
            //
            // print_r($v);
            // echo (' ]');

        } elseif (is_null($v)) {

            echo "[ isNull ]";

        } else {

            echo ('[ ');
            print_r($v);
            echo (' ]');
        }

        echo "<br/>";

    }

    ##############################
    # 超级管理员
    # 1 . 拥有 '系统管理员' 的权限
    # 2 . 新建 project (项目)
    # 3 . 设定 '系统管理员'
    #
    public static $adminOpenID =
        'r-aMG0QJ1HnNF6qEuXFejfSG0miw';
    # $userOpenID  测试用
    public static $userOpenID =
        'r-a8888888888888uXFejfSG0miw';

    ##############################
    # 一个特殊的<邀请码>
    # 用于 系统初始化
    #
    public static $初始化码 = '345';
    public static $清除数据 = '777';

    ##############################
    # 存储 已经读取的 cla_obj
    #
    public static $BUF = [];
    public static function BUF_save()
    {
        foreach (SYS::$BUF as $k1 => $v1) {
            foreach ($v1 as $k2 => $v2) {
                $v2->save();
            }
        }
    }

    ##############################
    # 现在时间
    #
    # 避免重复调用时间函数
    #
    public static $NOW;
    public static function getNow()
    {
        SYS::$NOW = date('Y-m-d H:i:s', strtotime('now'));
    }

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

    public static function 初始化JSON()
    {
        VAL::newOne('管理员IDArr', []);

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

    public static function is系统管理员_end()
    {
        if (!SYS::is系统管理员()) {
            $GLOBALS['RET']->不是管理员_end();
            exit();
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
        // $a   = SYS::getJSON('管理员IDArr');
        $a = VAL::get('管理员IDArr');

        $a[] = $UID;

        VAL::set('管理员IDArr', array_unique($a));

        // SYS::setJSON('管理员IDArr', array_unique($a));
    }

    ##############################
    #  移去一个 系统管理员
    #
    public static function remove_管理员($UID)
    {

        $a = VAL::get('管理员IDArr');

        VAL::set('管理员IDArr', array_diff($a, [$UID]));
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

SYS::getNow();
