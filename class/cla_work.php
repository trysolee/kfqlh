<?php


include_once "/tools/ret.php";
include_once '/tools/sdb.php';
include_once "/tools/sys.php";


class cla_work
{

    #=====================================
    # 获取一个 work by ID
    #
    public static function getByID($WID)
    {
        $o = new cla_work();

        $sql = "SELECT * FROM  pro_work "
            . " where WID = " . $WID;

        $o->DAT = SDB::SQL($sql);
        if (SDB::$notFind) {
            $GLOBALS['RET']->ID无效_end('cla_project');
            exit();
        }
        return $o;
    }

    //
    // 判断 一个 分类数组 [  '断枝清除', '补苗']
    // 是属于 那种分类 , 如 : '发现异常' , '日常工作'
    //
    // 返回的值 赋予 'type'
    //
    public static function theType($WT)
    {

        // 发现异常的 类型
        $arr = ['110', '113'];

        if (count(array_intersect($WT, $arr)) > 0) {
            return 1; // 发现异常
        }
        return 2; // 日常工作
    }

    //
    // 获得一个 新建的对象
    //
    public static function newWork($WT)
    {

        $t = strtotime('now');

        $dat = [
            'name' => 'pro_work', // table
            'DAT'  => [
                'JID'  => $_SESSION["JID"],
                'FT'   => $t,
                'CT'   => $t,
                'JSON' => [

                    '分组':$_SESSION['分组'],

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

                    'msg'   => [[
                        'UID' => 4,
                        'CT'  => '2018-01-14 11:23',
                        // 计算 path ( 跟据work 的 CT时间 )
                        // 20180114/23002.jpg
                        'pic' => 2,

                    ], [
                        'UID' => 4,
                        'CT'  => '2018-01-14 11:23',
                        'T'   => '天天气很好',
                    ]],
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
        $D['WID'] = SDB::$insertID;

        $o      = new cla_user();
        $o->DAT = $D;
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
        if ($this->is分组管理员()) {
            return;
        }
        $GLOBALS['RET']->错误终止_end('须管理员或发布者');
        exit();
    }

    ############################
    #
    #
    private function is分组管理员()
    {
        $p = $this->getProject();
        return
        $p->我是管理员($this->DAT['JSON']['分组']);
    }
    public function is分组管理员_end()
    {
        if (!$this->is分组管理员()) {
            $GLOBALS['RET']->错误终止_end('必须是管理员');
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
    # 关闭 帖子
    # ( 不让回复了 )
    #
    public function closeIt()
    {
        $this->DAT['JSON']['open'] = 2;
    }

    ############################
    # 屏蔽 帖子
    # ( 不让看了 )
    # 管理员还是可以看到
    #
    public function killIt()
    {
        $this->DAT['JSON']['open'] = 3;
    }

    ############################
    # 需要检查有没有上传'文件'
    #
    public function 检查_上传图片()
    {

        ###############################
        #
        # 如果 work 的经纬度 还没有设置
        # 用这张照片的 经纬度
        #

        if (empty($_FILES['pic'])) {
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
            strrchr($_FILES['pic']['name'], '.'),
            1);
        $DAT   = &$this->DAT;
        $JSON  = &$DAT['JSON'];
        $picID = $JSON['picID']++;

        // pic/20180114/23002.jpg
        $fn = 'pic/' .
        date('Ymd', $DAT['CT']) . '/' .
            $DAT['WID'] * 1000 + $picID . '.' .
            $exn;

        ###############################
        # 按名字保存文件
        #
        move_uploaded_file($_FILES['pic']['tmp_name'],
            $fn);

        ###############################
        # push 入 msg
        #
        $dat = [
            'UID' => $_SESSION['UID'],
            'CT'  => strtotime('now'),
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
        cal_pic::newOne(
            $this->getJID(),
            $_SESSION['UID'],
            $_FILES['pic']['size'],
            $_POST['lo'],
            $_POST['la'],
            'mobile',
            $fn
        );
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
            'CT'  => strtotime('now'),
            'T'   => $_POST['TXT'],
        ];
        array_push($this->DAT['JSON']['msg'], $dat);
    }
}
