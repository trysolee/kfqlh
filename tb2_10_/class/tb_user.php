<?php

include_once SYS::$filePath['sdb_one'];
include_once SYS::$filePath['openid'];
include_once SYS::$filePath['begin'];

class cla_user extends sdb_one
{

    #=====================================
    # 获取一个 user (自己)
    #
    public static function getMyself()
    {
        return cla_user::getByID($_SESSION['UID']);
    }

    #=====================================
    # 获取一个 user by ID
    #
    public static function getByID($UID)
    {
        $o = new cla_user();
        return $o->getObjByID_end($UID);
    }

    #=====================================
    # 创建 一个 user
    #
    public static function newOne($na, $JID //
        , $角色)
    {
        $o = new cla_user();
        $o->_NEW([
            //  UID
            'JID'  => $JID,
            'JSON' => [
                'NA'  => $na,
                // 孩子 , 家长 , 管理员 , 系统管
                '角色'  => $角色,
                'LT'  => SYS::$NOW,
                '执行包' => [
                    '类型' => '空闲',
                ],
            ],
            // 'FT'   => SYS::$NOW,
        ]);
        if ($o->is家长()) {
            $o->修改密码('123');
        };
        if ($o->is孩子()) {
            $o->set存款(600);
        };

        return $o;
    }

    public static function 添加好友($UID1, $UID2)
    {

    }

    public static function 我是管理员_end()
    {
        if (!$_SESSION["is管理员"]) {
            $GLOBALS['RET']->错误终止_end('不是管理员');
            exit();
        }

    }

    public static function del好友($UID)
    {
        //
        // 1 . 删除<db_user>
        // 2 . 删除<db_friend>
        //

    }

    public static function del家长($UID)
    {
        //
        // 1 . 删除<db_user>
        // 2 . 删除<db_openid>
        //

    }

    public static function del管理员($UID)
    {
        //
        // 如果只有一个<管理员>不能<del>
        //
        // 1 . 删除<db_user>
        // 2 . 删除<db_openid>
        //

    }

    #=====================================
    #
    # class
    #
    #=====================================

    public function _DB()
    {
        return 'user';
    }
    public function ID_name()
    {
        return 'UID';
    }
    public function ifFT()
    {
        return true;
    }
    public function fixed()
    {
        $this->fix = true;
        if (@$_SESSION['UID'] == $this->getUID()) {
            $GLOBALS['RET']->登录返回();
        }
    }

    #=====================================
    #
    # 上次登录时间
    public function getLT()
    {
        return $this->DAT['JSON']['LT'];
    }
    public function setLT()
    {
        $this->DAT['JSON']['LT'] = SYS::$NOW;
        $this->fixed();
    }

    #=====================================
    #
    #

    public function getUID()
    {
        return $this->DAT['UID'];
    }

    public function getUserName()
    {
        return $this->DAT['name'];
    }

    public function 家庭ID()
    {
        return $this->DAT['JID'];
    }

    public function is同一家庭()
    {
        return $this->DAT['JID'] == $_SESSION['家庭ID'];
    }

    public function is同一家庭_end()
    {
        if (!$this->is同一家庭()) {
            $GLOBALS['RET']->错误终止_end('不在同一个家庭');
        }
    }

    public function is孩子()
    {
        return $this->DAT['JSON']['角色'] == '孩子';
    }

    public function is孩子_end()
    {
        if (!$this->is孩子()) {
            $GLOBALS['RET']->错误终止_end('必须是 孩子');
        }
    }
    public function is家长()
    {

        if ($this->is管理员()) {
            return true;
        }

        return $this->DAT['JSON']['角色'] == '家长';
    }
    public function is家长_end()
    {
        if (!$this->is家长()) {
            $GLOBALS['RET']->错误终止_end('必须是 家长');
        }
    }
    public function is管理员()
    {
        return $this->DAT['JSON']['角色'] == '管理员';
    }
    public function is管理员_end()
    {
        if (!$this->is管理员()) {
            $GLOBALS['RET']->错误终止_end('必须是管理员');
        }
    }

    public function is任务中_end()
    {
        if ($this->DAT['JSON']['执行包']['类型'] == '空闲') {
            $GLOBALS['RET']->错误终止_end('不在任务中');
        }
    }

    public function 任务结束()
    {
        // $this->DAT['JSON']['任务中']           = false;
        $this->DAT['JSON']['执行包']['类型'] = '空闲';
        $this->fixed();
    }

    public function 改名($na)
    {

        $this->DAT['JSON']['NA'] = $na;
        $this->fixed();
    }

    public function 更新执行包($dat)
    {
        $this->DAT['JSON']['任务中'] = true;
        $this->DAT['JSON']['执行包'] = $dat;
        $this->fixed();
    }

    public function set存款($c)
    {
        $this->DAT['JSON']['存款'] = $c;
        $this->fixed();
    }

    public function 更新存款($c)
    {
        $this->DAT['JSON']['存款'] += $c;
        $this->fixed();
    }

    public function 修改密码($m)
    {

        $this->DAT['JSON']['短密'] = $m;
        $this->fixed();
    }

    public function 注销()
    {
        if ($this->is孩子()) {
            $S = &SYS::$DBNL;

            SDB::exec('delete from ' . $S['friend']
                . ' where UID = ' . $this->getUID()
            );
        }
        //
        $this->del();
    }

    public function 删除好友($uid)
    {
        $S = &SYS::$DBNL;

        SDB::exec('delete from ' . $S['friend']
            . ' where JID = ' . $this->家庭ID()
            . ' AND UID = ' . $uid
        );
    }
}
