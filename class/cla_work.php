<?php

include_once "/tools/ret.php";
include_once '/tools/sdb.php';
include_once "/tools/sys.php";
include_once "/class/cla_pic.php";
include_once "/class/cla_project.php";

class cla_work extends sdb_one
{

    #=====================================
    # 获取一个 work by ID
    #
    public static function getByID($WID)
    {
        $o = new cla_work();
        return $o->getObjByID($WID);
    }

    //
    // 获得一个 新建的对象
    //
    public static function newWork($WT)
    {

        cla_project::标签合法_end(
            $_SESSION['分组'],
            $WT);

        $o = new cla_work();
        $o->_NEW([
            'JID'  => $_SESSION["JID"],
            'FT'   => SYS::$NOW,
            'CT'   => SYS::$NOW,
            'JSON' => [

                '分组'    => $_SESSION['分组'],

                // 状态 ,
                // 1 :  开放回复 ,
                // 2 : 关闭回复
                // 3 : 屏蔽 ,
                'open'  => 1,

                // 如果: 新建时 没有照片 ,
                // 就没有 '经纬度'
                //
                // 等到有第一张照片 , 就用他的经纬度
                //
                'lo'    => -1, // 经度 //longitude
                'la'    => -1, // 维度 // latitude

                'UID'   => $_SESSION["UID"],

                // '标签'   => [119, 123],
                '标签'    => $WT,
                //归类

                'picID' => 1,

                'msg'   => [],
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
        return 'work';
    }
    public function ID_name()
    {
        return 'WID';
    }
    public function ifFT()
    {
        return true;
    }

    ############################
    #
    #
    private function is我发布的()
    {
        return $this->DAT['JSON']['UID']
            ==
            $_SESSION['UID'];
    }
    public function is我发布的_end()
    {
        if (!$this->is我发布的()) {
            $GLOBALS['RET']->错误终止_end('必须是自己发布');
            exit();
        }
    }

    ############################
    #
    #
    public function is我发布的or分组管理员_end()
    {
        if ($this->is我发布的()) {
            return;
        }
        if (cla_pro_user::is分组管理员(
            $this->getJID(),
            $this->get分组(),
            $_SESSION['UID'])
        ) {
            return;
        }
        
        $GLOBALS['RET']->错误终止_end('须管理员或发布者');
        exit();
    }

    ############################
    #
    #
    public function is当前项目_分组_end()
    {
        if ($this->getJID() != $_SESSION['JID']) {
            $GLOBALS['RET']->错误终止_end('不是当前项目');
            exit();
        }

        if ($this->get分组() != $_SESSION['分组']) {
            $GLOBALS['RET']->错误终止_end('不是当前分组');
            exit();
        }
    }

    ############################
    #
    #
    public function getJID()
    {
        return $this->DAT['JID'];
    }
    public function get分组()
    {
        return $this->DAT['JSON']['分组'];
    }

    ############################
    #
    #
    public function getProject()
    {
        return cla_project::getByID(
            $this->getJID()
        );
    }

    ############################
    # 重新设置 标签
    #
    public function 设置标签($ARR)
    {
        $this->DAT['JSON']['标签'] = $ARR;

        $this->fixed();
    }

    ############################
    # 关闭 帖子
    # ( 不让回复了 )
    #
    public function closeIt()
    {
        $this->DAT['JSON']['open'] = 2;

        $this->fixed();
    }

    ############################
    # 屏蔽 帖子
    # ( 不让看了 )
    # 管理员还是可以看到
    #
    public function killIt()
    {
        $this->DAT['JSON']['open'] = 3;

        $this->fixed();
    }

    ############################
    # 需要检查有没有上传'文件'
    #
    public function 检查_上传图片($F)
    {

        ###############################
        #
        # 如果 work 的经纬度 还没有设置
        # 用这张照片的 经纬度
        #

        if (empty($_FILES[$F])) {
            return; #没有 上传图片
        }
        ###############################
        # 参数检查
        #
        SYS::参数检查_end(['lo', 'la']);

        ###############################
        # 计算 服务端的'文件名'
        #
        $exn = substr(
            strrchr($_FILES[$F]['name'], '.'),
            1);

        $DAT   = &$this->DAT;
        $JSON  = &$DAT['JSON'];
        $picID = $JSON['picID']++;

        $PID = $DAT['WID'] * 100 + $picID;
        SYS::KK('计算PID', $PID);

        // pic/20180114/23002.jpg
        $fn = 'pic/' .
        date('Ymd', strtotime($DAT['CT'])) . '/' .
            $PID . '.' .
            $exn;

        SYS::KK('文件名 ', $fn);

        ###############################
        # 按名字保存文件
        #
        if (!is_dir(dirname($fn))) {
            mkdir(dirname($fn), 0777, true);
        }
        move_uploaded_file($_FILES[$F]['tmp_name'],
            $fn);

        ###############################
        # push 入 msg
        #
        $dat = [
            'UID' => $_SESSION['UID'],
            'CT'  => SYS::$NOW,
            'pic' => $picID,
        ];
        array_push($JSON['msg'], $dat);

        ###############################
        # 如果 之前没有记录经纬度
        # 现在记录
        #
        if ($JSON['lo'] < 0) {
            $JSON['lo'] = $_POST['lo'];
            $JSON['la'] = $_POST['la'];
        }

        ###############################
        # insert cla_pic
        #
        cla_pic::newOne(
            $PID,
            $this->getJID(),
            $_SESSION['UID'],
            $_FILES[$F]['size'],
            $_POST['lo'],
            $_POST['la'],
            'mobile',
            $fn
        );

        $this->fixed();
    }

    ############################
    # 需要检查有没有 上传'文字'
    #
    public function 检查_上传TXT()
    {
        if (empty($_POST['TXT'])) {
            return; #没有 上传文字
        }

        ###############################
        # push 入 msg
        #
        $dat = [
            'UID' => $_SESSION['UID'],
            'CT'  => SYS::$NOW,
            'T'   => $_POST['TXT'],
        ];
        array_push($this->DAT['JSON']['msg'], $dat);

        $this->fixed();
    }
}
