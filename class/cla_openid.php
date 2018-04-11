<?php
include_once "/tools/sys.php";
include_once "/tools/sdb.php";
include_once "/tools/ret.php";
include_once "/class/get_openid.php";

# openid

class cla_openid
{

    #=====================================
    # 创建 一个 'openid'
    #
    public static function newOne($openid, $UID)
    {

        $dat = [
            'name' => 'openid',
            'DAT'  => [
                'openid' => $openid,
                'UID'    => $UID,

            ],
        ];

        ###############################
        # insert
        #
        SDB::insert($dat);

        ###############################
        # 返回 '对象'
        #
        $o      = new cla_openid();
        $o->DAT = $dat['DAT'];
        return $o;
    }

    #=====================================
    # 当未注册 , 会产生 $notFind = true;
    # 不能当作'错误' 处理
    #
    public static function getByOpenid($openid)
    {
        $o         = new cla_openid();
        $o->openID = $openid;

        $sql = "SELECT * FROM  openid "
            . " where openid = \"" . $openid . "\"";

        $o->DAT = SDB::SQL($sql);
        $o->OK  = !SDB::$notFind;

        return $o;
    }

    #=====================================
    # 如果 openid 解析失败
    # 立即exit() , 返回'错误'信息
    #
    public static function getByCode_end($code)
    {
        if (SYS::$调试) {
            return cla_openid::getByOpenid(SYS::$adminOpenID);
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

    private $OK = false;
    #####################################
    #
    #
    public function 还没注册()
    {
        return !$this->OK;
    }

    #####################################
    # 判断是否 超级管理员 的 openid
    #
    public function is超级()
    {
        if (SYS::$调试) {
            return true;
        }

        return $this->$openID == SYS::$adminOpenID;
    }

    #####################################
    # 通过 openid 找到 user 返回
    #
    public function getUser()
    {
        if ($this->OK) {
            return cla_user::getByID($this->DAT['UID']);
        }

        return null;
    }

    #####################################
    # 返回 openid
    #
    public function getOpenID()
    {
        if ($this->OK) {
            return $this->DAT['UID'];
        }

        return null;
    }
}
