<?php

include_once '/tools/sdb.php';

abstract class sdb_one
{

    public $DAT;

    public $fix = false;

    ###################################
    # 返回 数据库的表名 的标志
    #
    # 不是真正的 表名
    #
    abstract public function _DB();

    abstract public function ID();

    abstract public function ID_name();

    #################################

    public function fixed()
    {
        $this->fix = true;
    }

    #################################
    # 返回 数据库的表名( 最新版本 )
    #
    private function _DBN()
    {
        return SYS::$DBNL[$this->_DB()];
    }

    public function save()
    {
        if ($this->fix) {
            SDB::update(
                [
                    'name'  => $this->_DBN(),
                    'DAT'   => $this->DAT,
                    'WHERE' => [
                        $this->ID_name() => $this->ID(),
                    ],
                ]
            );
        }
    }

    public static function addBUF()
    {
        SYS::$BUF[$this->_DB()]
        [$this->ID()] = $this;
    }

    #=====================================
    # 返回一个 OBJ
    #
    # 可能是 BUF 里面
    #
    # 也可能是在$this 基础上 读取 $DAT
    #
    public function getDatByID($ID)
    {
        if (!empty(SYS::$BUF[$this->_DB()]
            [$ID])) {

            return SYS::$BUF[$this->_DB()]
                [$ID];
        }

        $sql = "SELECT * FROM  " . $this->_DBN()
        . " where "
        . $this->ID_name() . " = "
            . $ID;

        $this->DAT = SDB::SQL($sql);
        if (SDB::$notFind) {
            $GLOBALS['RET']->ID无效_end($this->_DB());
        }

        $this->addBUF();
        return $this;
    }

}
