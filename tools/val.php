<?php

# 邀请码

include_once "/tools/sdb.php";
include_once "/tools/ret.php";

class VAL extends sdb_one
{

    #=====================================
    #
    #
    public static function newOne($n, $v)
    {
        $o = new VAL();
        $o->_NEW([
            $o->ID_name() => $n,
            'JSON'        => $v,
        ]);
        return $o;
    }

    #=====================================
    #
    #
    public static function get($name)
    {
        $a = new VAL();
        $o = $a->getObjByID_end($name);

        return $o->DAT['JSON'];
    }

    public static function set($n, $v)
    {


        $a = new VAL();
        $o = $a->getObjByID_end($n);

        $o->DAT['JSON'] = $v;
        $o->fixed();
        $o->save();
    }

    #=====================================
    #
    # class
    #
    #=====================================

    public function _DB()
    {
        return 'val';
    }

    public function ID_name()
    {
        return 'name';
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

}
