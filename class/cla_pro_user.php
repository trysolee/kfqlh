<?php

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
            . ' where JID=' . $JID;

        $d = SDB::SQL_s($sql);

        $a = [];
        foreach ($d as $k => $l) {

            $v = $l['JSON'];
            if (!empty($v[$分组])) {
                $a[$l['UID']] = $v[$分组]['role'];
            }
        }
        SYS::KK('项目人员权限', $a);
    }

    #=====================================
    # 当有 user 当前<项目.分组> 被踢出来
    # 需要 找一个<项目.分组>来 充当<当前>
    #
    public static function get任一个($UID)
    {
        $o = new cla_pro_user();
        return $o->选一个项目分组($UID);
    }

    #=====================================
    # 获取一个 当前 <项目> 的<分组s>相关信息
    #
    public static function getNow()
    {
        return cla_pro_user::getOne(
            $_SESSION['JID'],
            $_SESSION['UID']);
    }

    #=====================================
    # 获取一个 user by ID
    #
    public static function getOne($JID, $UID)
    {
        $o = new cla_pro_user();
        return $o->getObj($JID, $UID);
    }

    // 只能操作 当前 project
    public static function putOne($UID, $name,
        $JID, $分组, $inUID) {

        $o = cla_pro_user::getOne($JID, $UID);

        if (!$o->isOK()) {
            $o->_NEW([
                'UID'  => $UID,
                'JID'  => $JID,
                'JSON' => [

                ],
                'FT'   => SYS::$NOW,
            ]);
        }

        $o->加入分组($分组, $name, $inUID);
        return $o;
    }

    public static function 他是成员(
        $JID, $分组, $UID) {
        $o = cla_pro_user::getOne($JID, $UID);
        if (!$o->isOK()) {
            return false;
        };

        return $o->有分组($分组);
    }

    public static function 他是成员_end(
        $JID, $分组, $UID) {
        if (!cla_pro_user::他是成员(
            $JID, $分组, $UID)) {

            $GLOBALS['RET']->错误终止_end('不属于[ 项目.分组 ]');
            exit();
        }

    }

    public static function role合法性_end(
        $分组, $role) {
        if (count(array_diff($role, cla_project::$FK[$分组]['role'])) > 0) {
            $GLOBALS['RET']->错误终止_end('非法权限');
        }
    }

    public static function getRole(
        $JID, $分组, $UID) {
        $o = cla_pro_user::getOne($JID, $UID);
        if (!$o->isOK()) {
            return [];
        }
        return $o->role($分组);

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
        # $UID 原来必须在 分组里面
        #
        if (!cla_pro_user::他是成员(
            $JID, $分组, $UID)) {
            $GLOBALS['RET']->错误终止_end('增加了UID');
        }

        ############################
        # 权限 合法性
        #
        cla_pro_user::role合法性_end($分组, $role);

        $o = cla_pro_user::getOne($JID, $UID);
        $o->set_role($分组, $role);

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

        $o = cla_pro_user::getOne($JID, $UID);
        if (!$o->isOK()) {
            return false;
        }

        return $o->is管理员($分组);
    }

    #=====================================
    #
    # class
    #
    #=====================================

    public $JID;

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

    public function addBUF()
    {
        $JID = $this->DAT['JID'];
        $UID = $this->DAT['UID'];

        $id = $JID . '_' . $UID;

        SYS::$BUF[$this->_DB()]
        [$id] = $this;
    }

    public function getBUF($ID)
    {

        $JID = $this->JID;
        $UID = $ID;

        $id = $JID . '_' . $UID;

        if (!empty(SYS::$BUF[$this->_DB()]
            [$id])) {

            return SYS::$BUF[$this->_DB()]
                [$id];
        } else {
            return null;
        }
    }

    public function getObj($JID, $UID)
    {
        $this->JID = $JID;
        return $this->getObjByID($UID);
    }

    #=====================================
    #
    #
    public function 加入分组($分组, $name, $inUID)
    {
        if ($this->有分组($分组)) {
            // 如果已经是分组成员
            // 不要修改<分组>设置
            //
            return;
        }

        $this->DAT['JSON'][$分组] = [
            'name'   => $name,
            'role'   => cla_project::$FK[$分组]['roleNEW'],
            'inUser' => $inUID,
        ];

        $this->fixed();
    }

    public function 有分组($分组)
    {
        return !empty($this->DAT['JSON'][$分组]);
    }

    public function is管理员($分组)
    {
        return in_array('管理员',
            $this->role($分组)
        );
    }

    public function role($分组)
    {
        if (!$this->有分组($分组)) {
            return [];
        }
        return $this->DAT['JSON'][$分组]['role'];
    }

    public function set_role($分组, $role)
    {
        if (count($role) == 0) {

            unset($this->DAT['JSON'][$分组]);

            if (!$this->他在项目()) {
                $this->del();
            }

            $UID = $this->getUID();

            $u = cla_user::getByID($UID);
            if ($u->is当前项目分组($this->getJID()
                , $分组)) {
                $o = cla_pro_user::get任一个($UID);
                $u->set当前byPro_User($o);
            }

        } else {
            $this->DAT['JSON'][$分组]['role'] = $role;
        }
        $this->fixed();
    }

    public function 他在项目()
    {

        if (!$this->isOK()) {
            return false;
        }

        if (count($this->DAT['JSON']) < 1) {
            return false;
        }

        return true;
    }

    #=====================================
    # 当有 user 当前<项目.分组> 被踢出来
    # 需要 找一个<项目.分组>来 充当<当前>
    #
    public function 选一个项目分组($UID)
    {

        $sql = "SELECT * FROM  " . $this->_DBN()
            . ' where UID=' . $UID;

        $this->DAT = SDB::SQL($sql);

        if (SDB::$notFind) {
            // ready 初始化已经是false
            return $this;
        }

        // 如果 刚好 BUF里面存储了 $this
        // 那就直接返回 BUF 里面的
        $this->JID = $this->getJID();
        $o         = $this->getBUF($ID);
        if ($o) {
            return $o;
        }

        $this->ready = true;
        $this->addBUF();
        return $this;
    }

}
