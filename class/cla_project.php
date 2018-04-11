<?php

# == 不提供参数 ==
# 由于 seesion 里面记录 UID 和
# 当前 project ,
#
# 所以 , 关于project 权限的 判定
# 不需要再提供参数
#

include_once "/tools/sdb.php";
include_once "/tools/ret.php";
include_once "/tools/sys.php";

class cla_project extends sdb_one
{

    #=====================================
    #  BUF 设置
    #
    private static $BOX = [];
    public static function addBUF($obj)
    {
        cla_project::$BOX[$obj->ID()] = $obj;
    }
    public static function saveBUF()
    {
        foreach (cla_project::$BOX as
            $key => $value) {
            $value->save();
        }
    }

    #############################
    # 巡查系统框架
    #
    private static $FK = [

        '监理' => [
            'name'    => '监理单位',
            // 邀请人员时 , 新建的权限
            'roleNEW' => ['监理浏览'],
            'role'    => ['管理员', '监理巡查', '监理浏览', '监理维护'],
            'WT'      => [
                '100001' => '绿化垃圾',
                '100002' => '漂浮垃圾',
                '100011' => '补苗',
                '100013' => '绿篱修剪',
                '100015' => '榕树须根修剪',
                '100017' => '行道树1.5以下修剪',
                '100019' => '断枝清除',
                '100021' => '乔木倾倒扶正',
                '100023' => '草皮过高修剪',
            ],

            'user'    => [ // 记录在 SQL
                //'11' => ['监理巡查', '监理浏览', '监理维护'],
            ],
        ],

        '施工' => [
            'name'    => '施工单位',
            // 邀请人员时 , 新建的权限
            'roleNEW' => ['施工日常'],
            'role'    => ['管理员', '施工日常', '施工处理', '施工维护'],
            'WT'      => [
                '1'   => '绿化垃圾',
                '2'   => '漂浮垃圾',
                '110' => '补苗',
                '113' => '绿篱修剪',
                '115' => '榕树须根修剪',
                '117' => '行道树1.5以下修剪',
                '119' => '断枝清除',
                '121' => '乔木倾倒扶正',
                '123' => '草皮过高修剪',
            ],

            'user'    => [],
        ],

        '甲方' => [
            'name'    => '甲方单位',
            // 邀请人员时 , 新建的权限
            'roleNEW' => ['甲方巡查'],
            'role'    => ['管理员', '甲方巡查'],
            'WT'      => [
                '600001' => '绿化垃圾',
                '600002' => '漂浮垃圾',
                '600011' => '补苗',
                '600013' => '绿篱修剪',
                '600015' => '榕树须根修剪',
                '600017' => '行道树1.5以下修剪',
                '600019' => '断枝清除',
                '600021' => '乔木倾倒扶正',
                '600023' => '草皮过高修剪',
            ],

            'user'    => [],
        ],

        '临时' => [
            'name'    => '接口',
            // 邀请人员时 , 新建的权限
            'roleNEW' => ['监理浏览'],
            'role'    => ['管理员', '监理浏览'],
            'user'    => [
            ],
        ],

    ];

    #
    # 发布 'pic' ( 图片 ) 的权限判定
    private static $CR_NPic = [
        '监理巡查',
        '施工日常',
        '甲方巡查',
    ];

    #
    # 发布 'txt' ( 文字 ) 的权限判定
    private static $CR_NTxt = [
        '监理巡查',
        '施工日常',
        '甲方巡查',
    ];

    #
    #  新建 'work' ( 工作贴 ) 的权限判定
    private static $CR_NWork = [
        '监理巡查',
        '施工日常',
        '甲方巡查',
    ];

    #=====================================
    # 新建 'work' ( 工作贴 ) 的权限判定
    #
    # true => 有权限
    # false => 没有权限
    #
    public static function CR_newWork()
    {
        # code...
    }

    #=====================================
    # 发布 'pic' ( 图片 ) 的权限判定
    #
    # true => 有权限
    # false => 没有权限
    #
    public static function CR_newPic()
    {
        # code...
    }

    #=====================================
    # 发布 'txt' ( 文字 ) 的权限判定
    #
    # true => 有权限
    # false => 没有权限
    #
    public static function CR_newTxt()
    {
        # code...
    }

    #=====================================
    # 创建 一个 project
    # 返回 一个'cla_project' 对象
    #
    public static function newProject($name)
    {

        $t = strtotime('now');

        $dat = [
            'name' => 'projoct',
            'DAT'  => [
                'JSON' => [
                    '监理' => [
                        'name' => '监理单位',
                        'user' => [

                        ],
                    ],
                    '甲方' => [
                        'name' => '甲方单位',
                        'user' => [],
                    ],
                    '施工' => [
                        'name' => '施工单位',
                        'user' => [],
                    ],
                    '临时' => [
                        'name' => '相关单位',
                        'user' => [
                        ],
                    ],
                ],
                'name' => $name,
                'FT'   => $t,
            ],
        ];

        ###############################
        # insert
        #
        # 记录 insertID
        #
        SDB::insert($dat);
        $D        = $dat['DAT'];
        $D['JID'] = SDB::$insertID;

        $o      = new cla_project();
        $o->DAT = $D;

        cla_project::addBUF($o);
        return $o;
    }

    #=====================================
    # 获取一个 project by ID
    #
    public static function getByID($JID)
    {
        if (!empty(cla_project::$BOX[$JID])) {
            return cla_project::$BOX[$JID];
        }

        $o = new cla_project();

        $sql = "SELECT * FROM  projoct "
            . " where JID = " . $JID;

        $o->DAT = SDB::SQL($sql);
        if (SDB::$notFind) {
            $GLOBALS['RET']->ID无效_end('cla_project');
            exit();
        }
        cla_project::addBUF($o);

        return $o;
    }

    #=====================================
    # 获取一个 project 当前项目
    #
    # $JID 记录在 seesion 里面
    #
    public static function get当前()
    {
        return cla_project::getByID($_SESSION['JID']);
    }

    #=====================================
    #
    # class
    #
    #=====================================

    public function dbName()
    {
        return SYS::$DBNL['pro'];
    }
    public function ID()
    {
        return $this->DAT['JID'];
    }
    public function ID_name()
    {
        return 'JID';
    }

    private $OK = false;
    #####################################
    # 判断 上一个操作的 正确性
    #
    public function isOK()
    {

        ###############################
        # 判断 LT (lastTime) 的 合法性
        #
    }

    #####################################
    # 获得 user 的 role(权限)
    #
    # $UID 记录在 seesion 里面
    #
    public function getRole()
    {

        ###############################
        #
        # 返回 user 在 分组的权限
        #
        return $this->getRoleByUID($_SESSION['UID']);
    }
    public function getRoleByUID($UID)
    {

        print_r($this->DAT['JSON']
            [$_SESSION['分组']]);

        ###############################
        #
        # 返回 user 在 分组的权限
        #
        return $this->DAT['JSON']
            [$_SESSION['分组']]
            ['user']
            [$UID]
            ['role'];

    }

    #####################################
    #
    #
    public function 设置UID权限($分组, $UID, $role)
    {
        $this->DAT['JSON']
        [$分组]
        ['user']
        [$UID]
        ['role'] = $role;
    }
    public function 移去UID权限($分组, $UID)
    {
        unset($this->DAT['JSON']
            [$分组]
            ['user']
            [$UID]);
    }

    #####################################
    #
    #
    public function 被邀请进入分组($UID, $name, $分组, $inUID)
    {

        $this->DAT['JSON']
        [$分组]
        ['user']
        [$UID] = [
            'name'   => $name,
            'role'   => cla_project::$FK[$分组]['roleNEW'],
            'inUser' => $inUID,
        ];
    }

    #####################################
    #
    #
    public function 他是成员($分组, $UID)
    {
        return empty($this->DAT['JSON']
            [$分组]
            ['user']
            [$UID]
        );
    }
    public function 我是成员()
    {
        return $this->他是成员(
            $_SESSION['分组'],
            $_SESSION['UID']
        );
    }
    public function 他在项目($UID)
    {
        if ($this->他是成员('监理', $UID)) {
            return true;
        }
        if ($this->他是成员('甲方', $UID)) {
            return true;
        }
        if ($this->他是成员('施工', $UID)) {
            return true;
        }
        if ($this->他是成员('临时', $UID)) {
            return true;
        }
        return false;
    }

    #####################################
    #
    #
    public function 我是管理员_end()
    {

        if (!$this->我是管理员($_SESSION['分组'])) {
            $GLOBALS['RET']->错误终止_end('必须是管理员');
            exit();
        }
    }

    public function 我是管理员($分组)
    {

        ###############################
        #
        # 包括 : 超级管理员 和 系统管理员
        #
        # 分组管理员 考虑 当前分组
        #
        if (SYS::is系统管理员()) {
            return true;
        }

        if (in_array('管理员',
            $this->DAT['JSON']
            [$分组]
            ['user']
            [$_SESSION['UID']])) {
            return true;
        }

        return false;
    }
    #####################################
    #
    #
    public function fix某人权限($UID, $ARR)
    {

        ###############################
        #
        # 1 . 检查 $UID 原来在不在 project里面
        # ( 不能硬拉人进来 , 只能邀请 )
        #
        # 2 . 判断 $ARR是否 是否符合role定义
        #
        # 3 . '踢走' , 如果$ARR 长度为 0
        # 踢走这个成员
        #
        # 4 . 只能处理当前分组
        #

        $分组 = $_SESSION['分组'];

        ############################
        # $ARR 必须是 array
        #
        if (gettype($ARR != 'array')) {
            $R->错误终止_end('参数不是array(2)');
        }

        ############################
        # $UID 原来必须在 分组里面
        #
        if (!$this->他是成员($分组, $UID)) {
            $GLOBALS['RET']->错误终止_end('增加了UID');
        }

        ############################
        # 权限 合法性
        #
        if (count(array_diff($ARR, cla_project::$FK[$分组]['role'])) > 0) {
            $GLOBALS['RET']->错误终止_end('非法权限');
        }

        if (count($ARR) == 0) {
            ############################
            # 如果 $ARR长度为0 ,
            # 踢走着个 UID
            #
            $this->移去UID权限($分组, $UID);

            if (!$this->他在项目($UID)) {
                $u = cla_user::getByID($UID);
                $u->离开项目($this->getJID());
            }

        } else {
            ############################
            #设置新权限
            #
            $this->设置UID权限($分组, $UID, $ARR);
        }

    }

    #####################################
    #
    #
    public function fix项目名($name)
    {

        ###############################
        #
        # 1 . 判断 新名字的长度
        #
        # 2 . 在这里判断 权限
        #

        if (strlen($name) > SYS::$项目名_长度) {
            $GLOBALS['RET']->错误终止_end('项目名超长');
        }
        $this->DAT['name'] = $name;

    }

    #####################################
    #
    #
    public function fix分组名($name)
    {

        ###############################
        #
        # 1 . 判断 新名字的长度
        #
        # 2 . 在这里判断 权限
        #
        if (strlen($name) > SYS::$分组名_长度) {
            $GLOBALS['RET']->错误终止_end('分组名超长');
        }
        $this->DAT['JSON'][$_SESSION['分组']]['name'] = $name;
    }

    #####################################
    #
    #
    public function getJID()
    {

        ###############################
        #
        #
        return $this->DAT['JID'];
    }

}
