<?php

include_once SYS::$filePath['sdb_one'];

class cla_invite extends sdb_one
{

    #=====================================
    # 获取一个 cla_family by ID
    #
    public static function getByID_end($ID)
    {
        $o = new cla_invite();
        return $o->getObjByID_end($ID);
    }

    #=====================================
    # 创建 一个
    #
    public static function 新建家长邀请($JID)
    {
        $r = rand(111111, 999999);

        // SYS::KK('随机数', $r);

        $o = new cla_invite();
        $o->_NEW([
            $o->ID_name() => $r,
            'JSON'        => [
                '类型'  => '家长邀请',
                'JID' => $JID,
            ],
        ]);
        return $o;
    }

    public static function 新建好友邀请($JID)
    {
        $r = rand(111111, 999999);

        $o = new cla_invite();
        $o->_NEW([
            $o->ID_name() => $r,
            'JSON'        => [
                '类型'  => '好友邀请',
                'JID' => $JID,
            ],
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
        return 'invite';
    }
    public function ID_name()
    {
        return 'IID';
    }
    public function ifFT()
    {
        return true;
    }
    public function ID累加()
    {
        // 不累加 需要 重写
        // return false;
        return false;
    }
    #=====================================
    #
    #

    public function is家长邀请_end()
    {
        return $this->DAT['JSON']['类型'] == '家长邀请';
        $GLOBALS['RET']->错误终止_end('必须时家长邀请');
        exit();
    }
    public function is好友邀请_end()
    {

        return $this->DAT['JSON']['类型'] == '好友邀请';
        $GLOBALS['RET']->错误终止_end('必须好友邀请');
        exit();
    }
    public function getJID()
    {
        return $this->DAT['JSON']['JID'];
    }
    public function getID()
    {
        return $this->DAT[$this->ID_name()];
    }
}
