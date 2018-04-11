<?php

include_once '/tools/sdb.php';
include_once "/tools/ret.php";

class cla_pic extends sdb_one
{

    #=====================================
    #  BUF 设置
    #
    private static $BOX = [];
    public static function addBUF($obj)
    {
        cla_pic::$BOX[$obj->ID()] = $obj;
    }
    public static function saveBUF()
    {
        foreach (cla_pic::$BOX as
            $key => $value) {
            $value->save();
        }
    }

    #=====================================
    # 获取一个 user by ID
    #
    public static function getByID($PID)
    {
        $o = new cla_pic();

        $sql = "SELECT * FROM  pic "
            . " where PID = " . $PID;

        $o->DAT = SDB::SQL($sql);
        if (SDB::$notFind) {
            $GLOBALS['RET']->ID无效_end('cla_pic');
        }
        return $o;
    }

    #=====================================
    # 创建 一个 user
    #
    public static function newOne($JID,
        $UID, $size, $lo, $la,
        $car, $path) {
        $t = strtotime('now');

        $dat = [
            'name' => 'pic',
            'DAT'  => [
                'JID'  => $JID,
                'lo'   => $lo,
                'la'   => $la,
                'JSON' => [
                    'UID'  => $UID,
                    'size' => $size / 1024,
                    # mobile , car , fly
                    'car'  => $car,
                    'path' => $path,
                    'FT'   => $t,
                ],
            ],
        ];

        ###############################
        # insert
        #
        # 记录 insertID
        #
        SDB::insert($dat);
        $D        = $dat['DAT'];
        $D['PID'] = SDB::$insertID;

        $o      = new cla_pic();
        $o->DAT = $D;
        return $o;
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

}
