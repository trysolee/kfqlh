<?php

//  孩子cla

class cla_uc extends sdb_one
{

    #=====================================
    # 获取一个 user by ID
    #
    public static function getByID($UID)
    {
        $o = new cla_uc();
        return $o->getObjByID_end($UID);
    }

    #=====================================
    # 创建 一个 user
    #
    public static function newOne($na, $JID)
    {
        $o = new cla_uc();
        $o->_NEW([
            //  UID
            'JID'  => $JID,
            'JSON' => [
                'NA' => $na,
                // 孩子 , 家长 , 管理员 , 系统管
                // '角色'  => $角色,
                // 'LT' => SYS::$NOW,
            ],
            // 'FT'   => SYS::$NOW,
        ]);
        $o->set存款(60 * 120);

        return $o;
    }

    #=====================================
    #
    # class
    #
    #=====================================

    public function _DB()
    {
        return 'u_c';
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
        // 
        // 不在 class 里面考虑
        // $RET->ret_buf_min_end('孩子_更新', 1);
        // 
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

    public function 改名($na)
    {

        $this->DAT['JSON']['NA'] = $na;
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

    public function 注销()
    {

        $this->del();
    }

}
