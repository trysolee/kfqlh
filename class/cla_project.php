<?php

# == 不提供参数 ==
# 由于 seesion 里面记录 UID 和
# 当前 project ,
#
# 所以 , 关于project 权限的 判定
# 不需要再提供参数
#

include_once 'tools/sdb_one.php';

class cla_project extends sdb_one
{

    #############################
    # 巡查系统框架
    #
    public static $FK = [

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
        ],

        '临时' => [
            'name'    => '接口',
            // 邀请人员时 , 新建的权限
            'roleNEW' => ['监理浏览'],
            'role'    => ['管理员', '监理浏览'],
            'user'    => [],
            'WT'      => [],
        ],

    ];

    public static function 标签合法_end($分组, $标签)
    {

        // SYS::KK(' get 分组', $分组);

        $a = array_keys(cla_project::$FK[$分组]['WT']);

        $b = array_diff($标签, $a);

        if (count($b) > 0) {
            $GLOBALS['RET']->错误终止_end('非法标签');
            exit();
        }
    }

    #=====================================
    # 创建 一个 project
    # 返回 一个'cla_project' 对象
    #
    public static function newProject($name)
    {

        $o = new cla_project();
        $o->_NEW([
            'JSON' => [
                '监理' => [
                    'name' => '监理单位',

                ],
                '甲方' => [
                    'name' => '甲方单位',

                ],
                '施工' => [
                    'name' => '施工单位',
                    // 'user' => [],
                ],
                '临时' => [
                    'name' => '相关单位',

                ],
            ],
            'name' => $name,
            'FT'   => SYS::$NOW,
        ]);

        return $o;
    }

    #=====================================
    # 获取一个 project by ID
    #
    public static function getByID($JID)
    {
        $o = new cla_project();
        return $o->getObjByID_end($JID);
    }

    #=====================================
    #
    # 检查 <项目.分组> 是否存在
    # 并 返回这个 cla_project 
    #
    public static function 存在项目分组_end($JID, $分组)
    {

        $o = cla_project::getByID($JID);
        if (!$o->isOK()) {
            $GLOBALS['RET']->错误终止_end('项目不存在');
            exit();
        }
        if (!array_key_exists($分组
            , $o->DAT['JSON'])) {
            $GLOBALS['RET']->错误终止_end('分组不存在');
            exit();
        }
        return $o;
    }

    #=====================================
    # 获取一个 project 当前项目
    #
    # $JID 记录在 seesion 里面
    #
    # 333
    public static function get当前()
    {
        return cla_project::getByID($_SESSION['JID']);
    }

    #=====================================
    #
    # class
    #
    #=====================================

    public function _DB()
    {
        return 'pro';
    }
    public function ID_name()
    {
        return 'JID';
    }
    public function ifFT()
    {
        return true;
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

        $this->fixed();

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

        $this->fixed();
    }

    #####################################
    #
    #
    public function getJID()
    {
        return $this->DAT['JID'];
    }

}
