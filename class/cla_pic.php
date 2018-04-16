<?php

include_once '/tools/sdb.php';
include_once "/tools/ret.php";

class cla_pic extends sdb_one
{

    #=====================================
    # 获取一个 user by ID
    #
    public static function getByID($PID)
    {
        $o = new cla_pic();
        return $o->getObjByID_end($PID);
    }

    #=====================================
    # 创建 一个 user
    #
    public static function newOne($PID, $JID,
        $UID, $size, $lo, $la,
        $car, $path) {

        $o = new cla_pic();
        $o->_NEW([
            'PID'  => $PID,
            'JID'  => $JID,
            'lo'   => $lo,
            'la'   => $la,
            'JSON' => [
                'UID'  => $UID,
                'size' => $size / 1024,
                # mobile , car , fly
                'car'  => $car,
                'path' => $path,
                'FT'   => SYS::$NOW,
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
        return 'pic';
    }
    public function ID_name()
    {
        return 'PID';
    }
    public function ID累加()
    {
        // 不累加 需要 重写
        return false;
    }

}
