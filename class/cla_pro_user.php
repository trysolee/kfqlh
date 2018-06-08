<?php

include_once 'tools/sdb_one.php';
include_once 'class/cla_user.php';

class cla_pro_user extends sdb_one
{

    #=====================================
    # 调试用
    #
    public static function 项目_分组_权限()
    {

        $o = new cla_pro_user();

        $JID    = $_SESSION['JID'];
        $分组 = $_SESSION['分组'];

        $sql = "SELECT * FROM  " . $o->_DBN()
            . " where JID=" . $JID
            . " AND GRO='" . $分组 . "'";

        $d = SDB::SQL_s($sql);

        $a = [];
        foreach ($d as $k => $l) {

            $a[$l['UID']] = $l['JSON']['role'];

        }
        SYS::KK('项目人员权限', $a);
    }

    #=====================================
    # 获取一个 当前 <项目> 的<分组s>相关信息
    #
    public static function getNow()
    {
        return cla_pro_user::getOne(
            $_SESSION['JID'],
            $_SESSION['分组'],
            $_SESSION['UID']);
    }

    #=====================================
    # 获取一个 user by ID
    #
    public static function getOne($JID, $分组, $UID)
    {
        $o = new cla_pro_user();
        return $o->getObj($JID, $分组, $UID);
    }

    public static function getOne_end(
        $JID, $分组, $UID) {

        $o = new cla_pro_user();
        $o = $o->getObj($JID, $分组, $UID);
        if ($o->isOK()) {
            return $o;
        }

        //
        $GLOBALS['RET']->错误终止_end('不属于[ 项目.分组 ]');
        exit();

    }

    // 
    public static function putOne($UID, $name,
        $JID, $分组, $inUID) {

        $o = cla_pro_user::getOne($JID, $分组, $UID);

        if (!$o->isOK()) {
            $o->_NEW([
                'UID'  => $UID,
                'GRO'  => $分组,
                'JID'  => $JID,
                'JSON' => [
                    'name'   => $name,
                    'inUser' => $inUID,
                    'role'   => cla_project::$FK[$分组]['roleNEW'],
                ],
                'FT'   => SYS::$NOW,
            ]);
        }
        return $o;
    }

    // 只能操作 当前 project
    public static function get任一个($UID)
    {

        $o = new cla_pro_user();

        $sql = "SELECT * FROM  " . $o->_DBN()
            . " where UID=" . $UID;

        $o->DAT = SDB::SQL($sql);

        if (!SDB::$notFind) {

            $o->ready = true;
            $o->addBUF();

        }

        return $o;
    }

    public static function role合法性_end(
        $分组, $role) {
        if (count(array_diff($role, cla_project::$FK[$分组]['role'])) > 0) {
            $GLOBALS['RET']->错误终止_end('非法权限');
        }
    }

    public static function getRole(
        $JID, $分组, $UID) {
        $o = cla_pro_user::getOne($JID, $分组, $UID);
        if (!$o->isOK()) {
            return [];
        }
        return $o->get_role();

    }

    public static function setRole(
        $JID, $分组, $UID, $role) {

        ############################
        # $role 必须是 array
        #
        if (!is_array($role)) {
            $GLOBALS['RET']->错误终止_end('参数不是array(2)');
        }

        ############################
        # 权限 合法性
        #
        cla_pro_user::role合法性_end($分组, $role);

        ############################
        # $UID 原来必须在 分组里面
        #
        $o = cla_pro_user::getOne_end($JID, $分组, $UID);
        $o->set_role($role);

    }

    public static function 我是分组管理员_end()
    {

        $JID    = $_SESSION['JID'];
        $分组 = $_SESSION['分组'];
        $UID    = $_SESSION['UID'];

        cla_pro_user::is分组管理员_end(
            $JID, $分组, $UID);
    }

    public static function is分组管理员_end(
        $JID, $分组, $UID
    ) {
        if (!cla_pro_user::is分组管理员(
            $JID, $分组, $UID)) {
            $GLOBALS['RET']->错误终止_end('必须是管理员');
            exit();
        }

    }

    public static function is分组管理员(
        $JID, $分组, $UID
    ) {

        ###############################
        #
        # 包括 : 超级管理员 和 系统管理员
        #
        # 分组管理员 考虑 当前分组
        #
        if (SYS::is系统管理员()) {
            return true;
        }

        $o = cla_pro_user::getOne($JID, $分组, $UID);
        if (!$o->isOK()) {
            return false;
        }

        return $o->is管理员();
    }

    #=====================================
    #
    # class
    #
    #=====================================

    public $JID, $分组;

    public function _DB()
    {
        return 'pro_user';
    }
    public function ID_name()
    {
        return 'UID';
    }
    public function ifFT()
    {
        return true;
    }
    public function ID累加()
    {
        // 不累加 需要 重写
        return false;
        // return true;
    }

    public function _WHERE($ID)
    {

        return "JID=" . $this->JID
        . " AND GRO='" . $this->分组 . "'"
            . " AND UID=" . $ID;
    }

    public function getJID()
    {
        return $this->DAT['JID'];
    }
    public function getUID()
    {
        return $this->DAT['UID'];
    }
    public function get分组()
    {
        return $this->DAT['GRO'];
    }

    public function addBUF()
    {
        $JID    = $this->DAT['JID'];
        $UID    = $this->DAT['UID'];
        $分组 = $this->DAT['GRO'];

        $id = $JID . $分组 . $UID;

        SYS::$BUF[$this->_DB()]
        [$id] = $this;
    }

    public function getBUF($ID)
    {

        $JID    = $this->JID;
        $分组 = $this->分组;
        $UID    = $ID;

        $id = $JID . $分组 . $UID;

        // $o = SYS::$BUF[$this->_DB()]
        //     [$id];
        // SYS::KK('getBUF', $o);

        return @SYS::$BUF[$this->_DB()]
            [$id];

    }

    public function getObj($JID, $分组, $UID)
    {
        $this->JID    = $JID;
        $this->分组 = $分组;
        return $this->getObjByID($UID);
    }

    #=====================================
    #
    #

    public function is管理员()
    {
        return in_array('管理员',
            $this->get_role()
        );
    }

    public function get_role()
    {
        return $this->DAT['JSON']['role'];
    }

    public function set_role($role)
    {
        // length == 0 // 删除 role
        if (count($role) == 0) {

            $this->del();

            // 如果 user 被当前<JID.分组> 提出
            // 清空 user的<当前项目.分组>数据
            // 等待下次登录时 , 随机挑选一个
            // 作为<当前项目.分组>
            //
            $UID = $this->getUID();

            $u = cla_user::getByID($UID);
            if ($u->is当前项目分组($this->getJID()
                , $this->get分组())) {

                $u->free当前项目分组();
            }

        } else {
            $this->DAT['JSON']['role'] = $role;
        }
        $this->fixed();
    }

}
