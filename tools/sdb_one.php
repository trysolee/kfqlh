<?php

include_once "/tools/ret.php";
include_once '/tools/sdb.php';
include_once "/tools/sys.php";

abstract class sdb_one
{

    public $DAT;

    public $fix  = false;
    public $del_ = false;

    //
    public $ready = false;
    public function isOK()
    {
        return $this->ready;
    }

    ###################################
    # 返回 数据库的表名 的标志
    #
    # 不是真正的 表名
    #
    abstract public function _DB();

    abstract public function ID_name();

    #################################
    #
    #
    public function ID是str()
    {
        return false;
    }
    public function ifFT()
    {
        return false;
    }

    public function ID()
    {
        if (empty($this->DAT[$this->ID_name()])) {
            SYS::KK('主键 null', $this->_DB());
            SYS::KK('DAT is ', $this->DAT);
        }

        return $this->DAT[$this->ID_name()];
    }

    public function ID累加()
    {
        // 不累加 需要 重写
        // return false;
        return true;
    }

    public function fixed()
    {
        $this->fix = true;
    }

    public function del()
    {
        $this->del_ = true;
    }

    #################################
    #
    #
    public function _NEW($DAT)
    {
        SDB::insert([
            'name' => $this->_DBN(),
            'DAT'  => $DAT,
        ]);

        $this->DAT = &$DAT;
        $D         = &$DAT;
        if ($this->ID累加()) {
            $D[$this->ID_name()] = SDB::$insertID;
        }

        $this->addBUF();
        $this->ready = true;
    }

    #################################
    # 返回 数据库的表名( 最新版本 )
    #
    public function _DBN()
    {
        return SYS::$DBNL[$this->_DB()];
    }

    public function save()
    {
        if ($this->del_) {

            SYS::KK('del ' . $this->_DB(), $this->DAT);

            $sql = "delete from " . $this->_DBN()
            . ' where '
            . $this->_WHERE($this->ID());

            SDB::exec($sql);
            $this->del_ = false;
            return;
        }

        if ($this->fix) {

            SYS::KK('save ' . $this->_DB(), $this->DAT);

            if ($this->ifFT()) {
                $this->DAT['FT'] = SYS::$NOW;
            }

            SDB::update(
                [
                    'name'     => $this->_DBN(),
                    'DAT'      => $this->DAT,
                    'SQLWhere' =>
                    $this->_WHERE($this->ID()),

                ]
            );

            $this->fix = false;
        }
    }

    public function addBUF()
    {
        SYS::$BUF[$this->_DB()]
        [$this->ID()] = $this;
    }

    public function getBUF($ID)
    {
        if (!empty(SYS::$BUF[$this->_DB()]
            [$ID])) {

            return SYS::$BUF[$this->_DB()]
                [$ID];
        } else {
            return null;
        }
    }
    #=====================================
    # 返回一个 OBJ
    #
    # 可能是 BUF 里面
    #
    # 也可能是在$this 基础上 读取 $DAT
    #
    public function getObjByID_end($ID)
    {
        $o = $this->getObjByID($ID);
        if (!$o->ready) {
            SYS::KK('ID无效 DB名', $this->_DB());
            SYS::KK('ID无效 ID值', $this->ID());

            $GLOBALS['RET']->ID无效_end($this->_DB());
        }

        return $o;
    }

    public function getObjByID($ID)
    {
        if (!$ID) {
            return;
        }

        $o = $this->getBUF($ID);
        if ($o) {
            return $o;
        }

        $sql = "SELECT * FROM  " . $this->_DBN()
        . ' where ' . $this->_WHERE($ID);

        $this->DAT = SDB::SQL($sql);

        if (SDB::$notFind) {
            // ready 初始化已经是false
            return $this;
        }

        $this->ready = true;
        $this->addBUF();
        return $this;
    }

    public function _WHERE($ID)
    {
        // 如果 ID 是 string 需要加 '引号'
        $FG = '';
        if ($this->ID是str()) {
            $FG = '\'';
        }

        return $this->ID_name() . " = " . $FG
            . $ID . $FG;
    }
}
