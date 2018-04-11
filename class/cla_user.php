<?php

include_once "/tools/ret.php";
include_once '/tools/sdb.php';
include_once '/tools/sdb_one.php';
include_once '/tools/begin.php';
include_once "/tools/sys.php";

class cla_user extends sdb_one
{
    #=====================================
    #  BUF 设置
    #
    private static $BOX = [];
    public static function addBUF($obj)
    {
        cla_user::$BOX[$obj->ID()] = $obj;
    }
    public static function saveBUF()
    {
        foreach (cla_user::$BOX as
            $key => $value) {
            $value->save();
        }
    }

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

        if (!empty(cla_user::$BOX[$UID])) {
            return cla_user::$BOX[$UID];
        }

        $o = new cla_user();

        $sql = "SELECT * FROM  user "
            . " where UID = " . $UID;

        $o->DAT = SDB::SQL($sql);
        if (SDB::$notFind) {
            $GLOBALS['RET']->ID无效_end('cla_user');
            exit();
        }
        $o->OK = true;

        cla_user::addBUF($o);
        return $o;
    }

    #=====================================
    # 获取一个 user by OpenID
    #
    public function getByOpenID($OID)
    {
        #
        # 通过 OpenID 建立 User 对象
        #

        $u = new cla_user();

        $sql = "SELECT a.* FROM user as a , openid as b"
            . " where b.openid = \"" . $OID . "\""
            . " and b.UID = a.UID";

        $u->DAT = SDB::SQL($sql);
        $u->OK  = !SDB::$notFind;

        cla_user::addBUF($u);
        return $u;
    }

    #=====================================
    # 创建 一个 user
    #
    public static function newOne($userName,
        $JID, $分组) {
        $t = strtotime('now');

        $dat = [
            'name' => 'user',
            'DAT'  => [
                'name' => $userName,

                'JSON' => [
                    'JID'  => $JID, # 当前项目
                    '分组'   => $分组, # 当前分组
                    'JIDs' => [$JID],
                ],
                'FT'   => $t,
                'LT'   => $t,
            ],
        ];

        ###############################
        # insert
        #
        # 记录 insertID
        #
        SDB::insert($dat);
        $D        = $dat['DAT'];
        $D['UID'] = SDB::$insertID;

        $o      = new cla_user();
        $o->DAT = $D;

        $o->OK = true;

        cla_user::addBUF($o);
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
    public function ID()
    {
        return $this->DAT['UID'];
    }
    public function ID_name()
    {
        return 'UID';
    }

    private $OK = false;
    #####################################
    # 判断 上一个操作的 正确性
    #
    public function isOK()
    {
        return $this->OK;
    }

    #=====================================
    #
    #
    public function getLT()
    {
        return date('Y-m-d H:i:s', $this->DAT['LT']);
    }
    public function setLT()
    {
        $this->DAT['LT'] = strtotime('now');
    }

    #=====================================
    #
    #
    public function 加入项目($JID)
    {
        $a   = &$this->DAT['JSON']['JIDs'];
        $a[] = $JID;
        $a   = array_unique($a);
    }
    public function 离开项目($JID)
    {
        $a = &$this->DAT['JSON']['JIDs'];
        $a = array_diff($a, [$JID]);
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

    }

    public function getUID()
    {
        return $this->DAT['UID'];
    }

}
