<?php

include_once "/tools/ret.php";
include_once '/tools/sdb.php';
include_once '/tools/begin.php';
include_once "/tools/sys.php";

class cla_user
{

    #=====================================
    # 各种 static function OK or Not
    #
    public static $isOK = false;

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

        $sql = "SELECT * FROM  user "
            . " where UID = " . $UID;

        $o->DAT = SDB::SQL($sql);
        if (SDB::$notFind) {
            $GLOBALS['RET']->ID无效_end('cla_user');
            exit();
        }
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
    }

    #=====================================
    # 创建 一个 user
    #
    public static function newOne($userName,
        $JID, $分组, $UID) {
        $t = strtotime('now');

        $dat = [
            'name' => 'user',
            'DAT'  => [
                'name' => $userName,
                #####################
                # JID 不放入 JSON
                # 是为了方便 select 返回数据
                #
                'JID'  => $JID, # 当前项目
                'JSON' => [
                    '分组'   => $分组, # 当前分组
                    'JIDs' => [
                        $JID => [
                            '分组'       => [$分组],
                            'inUserID' => $UID, # 介绍人
                        ],
                    ],
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
    }

    #=====================================
    #
    # class
    #
    #=====================================

    public $DAT;

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
    public function get当前项目ID()
    {
        return $this->DAT['JID'];
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
    public function get当前分组()
    {
        return $this->DAT['JSON']['分组'];
    }

    public function set当前项目分组($JID, $分组)
    {
        # code...
        #
        # 先判断 自己 是不是
        # 这个项目.分组的成员
        # ( 在user 里面 有保存相关数据 )
        # 直接调用 is成员() 就OK
        #
        if ($this->is成员($JID, $分组)) {
            $this->DAT['JID']            = $JID;
            $this->DAT['JSON']['分组'] = $分组;
        }

    }

    #=====================================
    #
    #
    public function is成员($JID, $分组)
    {
        # #######################
        #
        # 当遇到 '超级管理员' 或 '系统管理员'
        # 返回 true
        #
        if (SYS::is系统管理员()) {
            return true;
        }

        if (empty($this->DAT['JSON']
            ['JIDs']
            [$JID]
            ['分组']
            [$分组])) {
            return false;
        }

        return true;
    }

    public function add成员($JID, $分组, $inUID)
    {
        # code...
        #
        # 把自己 加入到
        # 某个项目.分组 里面
        # ( 处理 user里面的数据 )
        #
        if (empty($this->DAT['JSON']
            ['JIDs']
            [$JID])) {

            $this->DAT['JSON']
            ['JIDs']
            [$JID] = [
                '分组'       => [$分组],
                'inUserID' => $inUID, # 介绍人
            ];
        } else {
            $arr = &$this->DAT['JSON']
                ['JIDs']
                [$JID]
                ['分组'];

            if (empty($arr[$分组])) {
                array_push($arr, $分组);
            }
        }
    }

    public function remove成员($JID, $分组)
    {
        # code...
        #
        # 把自己
        # 移出 某个项目.分组
        # ( 处理 user里面的数据 )
        #
        if (empty($this->DAT['JSON']
            ['JIDs']
            [$JID]
            ['分组']
            [$分组])) {

            $arr = &$this->DAT['JSON']
                ['JIDs']
                [$JID]
                ['分组'];

            unset($arr[$分组]);
            if (count($arr) <= 0) {
                unset($this->DAT['JSON']
                    ['JIDs']
                    [$JID]);
            }
        }
    }

    public function getUID()
    {
        return $this->DAT['UID'];
    }

}
