<?php

include_once SYS::$filePath['sdb_one'];

class cla_family extends sdb_one
{

    #=====================================
    # 获取一个 cla_family by ID
    #
    public static function getByID($JID)
    {
        $o = new cla_family();
        return $o->getObjByID_end($JID);
    }

    #=====================================
    # 创建 一个 cla_family
    #
    public static function newOne($na)
    {
        $o = new cla_family();
        $o->_NEW([
            'na' => $na,
        ]);
        return $o;
    }

   public static function 尝试删除家庭($UID)
    {
        // 
        // 1 . 删除<db_user>
        // 2 . 删除<db_friend>
        // 
      
    }

    #=====================================
    #
    # class
    #
    #=====================================

    public function _DB()
    {
        return 'family';
    }
    public function ID_name()
    {
        return 'JID';
    }

    #=====================================
    #
    #

    public function getJID()
    {
        return $this->DAT['JID'];
    }

    public function getNA()
    {
        return $this->DAT['na'];
    }
}
