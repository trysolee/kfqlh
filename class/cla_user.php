<?php

include_once 'tools/sdb_one.php';
include_once 'tools/begin.php';
include_once "class/cla_openid.php";

class cla_user extends sdb_one
{

    #=====================================
    # 获取一个 user (自己)
    #
    public static function getMyself()
    {
        return cla_user::getByID($_SESSION['UID']);
    }

    #=====================================
    # 获取一个 user by ID
    #
    public static function getByID($UID)
    {
        $o = new cla_user();
        return $o->getObjByID_end($UID);
    }

    #=====================================
    # 创建 一个 user
    #
    public static function newOne($userName)
    {

        $o = new cla_user();
        $o->_NEW([
            'name' => $userName,
            'JSON' => [
                'JID' => -1,
            ],
            'FT'   => SYS::$NOW,
            'LT'   => SYS::$NOW,
        ]);

        return $o;
    }

    #=====================================
    #
    # class
    #
    #=====================================

    public function _DB()
    {
        return 'user';
    }
    public function ID_name()
    {
        return 'UID';
    }
    public function ifFT()
    {
        return true;
    }
    public function fixed()
    {
        $this->fix = true;
        if (@$_SESSION['UID'] == $this->getUID()) {
            $GLOBALS['RET']->登录返回();
        }
    }

    #=====================================
    #
    #
    public function getLT()
    {

        return $this->DAT['LT'];
    }
    public function setLT()
    {
        $this->DAT['LT'] = SYS::$NOW;
        $this->fixed();
    }

    #=====================================
    #
    #
    public function get当前项目ID()
    {
        return $this->DAT['JSON']['JID'];

    }
    public function get当前分组()
    {
        return $this->DAT['JSON']['分组'];
    }

    public function free当前项目分组()
    {

        $this->DAT['JSON']['JID'] = -1;
        // $this->DAT['JSON']['分组'] = $分组;
        $this->fixed();
    }

    public function set当前项目分组($JID, $分组)
    {

        $this->DAT['JSON']['JID']    = $JID;
        $this->DAT['JSON']['分组'] = $分组;
        $this->fixed();
    }

    public function set当前byPro_User($obj)
    {

        $this->set当前项目分组(
            $obj->getJID(),
            $obj->get分组()
        );

        $this->fixed();
    }

    public function is当前项目分组($JID, $分组)
    {

        if ($this->DAT['JSON']['JID'] != $JID) {
            return false;
        }

        if ($this->DAT['JSON']['分组'] != $分组) {
            return false;
        }

        return true;
    }

    public function getUID()
    {
        return $this->DAT['UID'];
    }

    public function getUserName()
    {
        return $this->DAT['name'];
    }

}
