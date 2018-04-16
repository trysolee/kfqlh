<?php

include_once "/tools/ret.php";
include_once '/tools/sdb.php';
include_once '/tools/begin.php';
include_once "/tools/sys.php";

include_once "/class/cla_openid.php";

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
    public static function newOne($userName,
        $JID, $分组) {

        $o = new cla_user();
        $o->_NEW([
            'name' => $userName,

            'JSON' => [
                'JID'  => $JID, # 当前项目
                '分组'   => $分组, # 当前分组
                'JIDs' => [$JID],
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

    }

    #=====================================
    #
    #
    public function 加入项目($JID)
    {
        $a   = &$this->DAT['JSON']['JIDs'];
        $a[] = $JID;
        $a   = array_unique($a);

        $this->fixed();
    }
    public function 离开项目($JID)
    {
        $a = &$this->DAT['JSON']['JIDs'];
        $a = array_diff($a, [$JID]);

        $this->fixed();
    }

    #=====================================
    #
    #
    public function get当前项目ID()
    {
        return $this->DAT['JSON']['JID'];

    }

    #=====================================
    #
    #
    public function get当前分组()
    {
        return $this->DAT['JSON']['分组'];
    }

    public function set当前项目分组($JID, $分组)
    {

        $this->DAT['JSON']['JID']    = $JID;
        $this->DAT['JSON']['分组'] = $分组;
        $this->fixed();
    }

    public function getUID()
    {
        return $this->DAT['UID'];
    }

}
