<?php

include_once SYS::$filePath['sdb_one'];
include_once SYS::$filePath['get_openid'];

// include_once "class/get_openid.php";
// include_once "class/cla_user.php";
# openid

class cla_openid extends sdb_one
{
    //
    // Session 加载时会判断是否已经<登录>
    // 如果没有<登录>会立即终止
    //
    // 但是,如果在<登录.页面> ,
    // 没有<登录> 还要继续往下执行
    //
    // <Session>会根据是否加载了<openid>
    // 来判断是否在<登录类型页面>
    //
    // 换句话 :
    // 只要同时加载<openid> 和 <Session>
    // 就算还没登录 , 也不会<立即终止>
    //
    //// false , 如果没有登录 , 立即终止
    public static $Session标记 = true;

    #=====================================
    # 创建 一个 'openid'
    #
    public static function newOne($openid, $UID)
    {

        $o = new cla_openid();
        $o->_NEW([
            $o->ID_name() => $openid,
            'UID'         => $UID,

        ]);

        return $o;
    }

    #=====================================
    # 当未注册 , 会产生 $notFind = true;
    # 不能当作'错误' 处理
    #
    # 未注册 时 : isOK() = false;
    # 为了方便 getOpenID()
    # 需要把 openid 记录下来
    #
    public static function getByOpenid($openid)
    {
        $o         = new cla_openid();
        $d         = $o->getObjByID($openid);
        $d->openID = $openid;
        return $d;
    }

    #=====================================
    # 如果 openid 解析失败
    # 立即exit() , 返回'错误'信息
    #
    public static function getByCode_end($code)
    {
        if (SYS::$openid不解析) {
            return cla_openid::getByOpenid($code);
        }

        openID::get($code);
        if (openID::$OK) {

            return cla_openid::getByOpenid(openID::$openid);
        } else {

            $GLOBALS['RET']->codeERR_end();
            exit();
        }
    }

    #=====================================
    #
    # class
    #
    #=====================================
    private $openID;

    public function _DB()
    {
        return 'openid';
    }
    public function ID_name()
    {
        return 'openid';
    }

    public function ID累加()
    {
        // 主键 不累加
        return false;
    }
    public function ID是str()
    {
        return true;
    }

    #####################################
    #
    #
    public function 还没注册()
    {
        return !$this->isOK();
    }

    #####################################
    # 判断是否 超级管理员 的 openid
    #
    public function is超级()
    {

        return $this->openID == SYS::$adminOpenID;
    }

    #####################################
    # 通过 openid 找到 user 返回
    #
    public function getUser()
    {
        if ($this->isOK()) {
            return cla_user::getByID($this->DAT['UID']);
        }

        return null;
    }

    #####################################
    # 返回 openid
    #
    public function getOpenID()
    {
        return $this->openID;
    }

    #####################################
    # 返回 UID
    #
    public function getUID()
    {
        return $this->DAT['UID'];
    }
}
