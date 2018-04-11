<?php

# 邀请码

include_once "/tools/sdb.php";
include_once "/tools/ret.php";

class cla_in
{

    #=====================================
    # 创建 一个 '邀请数据'
    #
    # 不返回 新建的'对象'
    #
    public static function newOne()
    {

        $t = strtotime('now');

        $dat = [
            'name' => 'pro_in',
            'DAT'  => [
                # INID  ::
                'JSON' => [
                    'UID' => $_SESSION['UID'],
                    'JID' => $_SESSION['JID'],
                    '分组'  => $_SESSION['分组'],
                ],
                'FT'   => $t,
            ],
        ];

        ###############################
        # insert
        #
        # 记录 insertID
        #
        SDB::insert($dat);
        $INID = SDB::$insertID;

        ###############################
        # RET 里面记录 新建的 '邀请码'
        #
        $GLOBALS['RET']->返回邀请码($INID);
    }

    #=====================================
    # 通过 '邀请码' 获取 邀请数据
    #
    public static function getByIN_end($INID)
    {
        $sql = "SELECT *  FROM pro_in  where INID = " . $INID;

        $d      = new cla_in();
        $d->DAT = SDB::SQL($sql);
        if (SDB::$notFind) {
            $GLOBALS['RET']->ID无效_end('cla_IN');
            exit();
        }
        return $d;
    }

    #=====================================
    #
    # class
    #
    #=====================================

    private $DAT;

    private $OK = false;
    #####################################
    #
    #
    public function isOK()
    {
        return $this->OK;
    }

    #####################################
    #
    # 1 . 新建user
    #
    # 2 . 新建openid
    #
    # 3 . 这里的 $openCla 是
    # 有 openid
    # 没有 UID 的
    #
    public function 首次邀请($openCla, $userName)
    {
        #####################################
        # 用 userName
        # JID
        # 分组
        # 介绍人
        #
        # 创建一个 user
        #
        $JID       = $this->DAT['JSON']['JID'];
        $分组    = $this->DAT['JSON']['分组'];
        $邀请人 = $this->DAT['JSON']['UID'];

        $openid = $openCla->getOpenID();

        $u = cla_user::newOne(
            $userName,
            $JID,
            $分组
        );
        Session::set($u, $openid);

        #####################################
        # 用 openid , UID
        #
        # 创建一个 openid CLASS
        #
        $o = cla_openid::newOne(
            $openid,
            $u->getUID()
        );

        #####################################
        # 通过 JID get project
        #
        # 把 UID 加入到指定'分组'
        #
        $j = cla_project::getByID($JID);
        $j->被邀请进入分组(
            $u->getUID(),
            $userName,
            $分组,
            $邀请人
        );

    }

    #####################################
    #
    #
    public function 二次邀请($openCla, $userName)
    {
        $JID       = $this->DAT['JSON']['JID'];
        $分组    = $this->DAT['JSON']['分组'];
        $邀请人 = $this->DAT['JSON']['UID'];

        #####################################
        # 通过 openid 找到 user
        #
        $u = $openCla->getUser();
        $u->加入项目($JID);
        $u->set当前项目分组($JID, $分组);

        #####################################
        # 通过 JID 找到 project
        #
        # 设置 project 里面的数据
        # 在对应的分组里面 添加成员
        #
        $j = cla_project::getByID($JID);

        ###############################
        # 需要避免 二次扫描
        # 把前面设置好的权限 冲掉了
        #
        if (!$j->他是成员($分组, $u->getUID())) {
            $j->被邀请进入分组(
                $u->getUID(),
                $userName,
                $分组,
                $邀请人
            );
        }

        Session::set($u, $openCla->getOpenID());
    }
}
