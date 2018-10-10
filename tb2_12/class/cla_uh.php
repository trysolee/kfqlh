<?php

//  家长

include_once SYS::$filePath['openid'];
// include_once SYS::$filePath['begin'];

class cla_uh extends sdb_one
{

    #=====================================
    # 获取一个 user (自己)
    #
    public static function getMyself()
    {
        return cla_uh::getByID($_SESSION['UID']);
    }

    #=====================================
    # 获取一个 user by ID
    #
    public static function getByID($UID)
    {
        $o = new cla_uh();
        return $o->getObjByID_end($UID);
    }

    #=====================================
    # 创建 一个 user
    #
    public static function newOne($na, $JID, $角色)
    {
        $o = new cla_uh();
        $o->_NEW([
            //  UID
            'JID'  => $JID,
            'JSON' => [
                'NA' => $na,
                // 孩子 , 家长 , 管理员 , 系统管
                '角色' => $角色,
                'LT' => SYS::$NOW,
            ],
            // 'FT'   => SYS::$NOW,
        ]);
        $o->修改密码('123');
        return $o;
    }

    public static function 我是管理员_end()
    {
        if (!$_SESSION["is管理员"]) {
            RET::错误终止_end('不是管理员');
            // exit();
        }

    }

    #=====================================
    #
    # class
    #
    #=====================================

    public function _DB()
    {
        return 'u_h';
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

        // 不在 class 里面考虑
        // if (@$_SESSION['UID'] == $this->getUID()) {
        //     $RET->ret_buf_min_end('自己_重置', 1);
        // }
        //
        // 不在 class 里面考虑
        // $RET->ret_buf_min_end('孩子_更新', 1);
        //
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
            RET::错误终止_end('不在同一个家庭');
        }
    }

    public function is管理员()
    {
        return $this->DAT['JSON']['角色'] == '管理员';
    }
    public function is管理员_end()
    {
        if (!$this->is管理员()) {
            RET::错误终止_end('必须是管理员');
        }
    }

    public function 改名($na)
    {

        $this->DAT['JSON']['NA'] = $na;
        $this->fixed();
    }

    public function 修改密码($m)
    {

        $this->DAT['JSON']['短密'] = $m;
        $this->fixed();
    }

    public function 注销()
    {
        $S = &SYS::$DBNL;

        SDB::exec('delete from '
            . $S['openid']
            . ' where UID = '
            . $this->getUID()
        );

        //
        $this->del();
    }
}
