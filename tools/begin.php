<?php

###########################
# 系统初始化时 , 需要用到
#
# 步骤 :
#
# 我登录 , 发现未注册 , 返回
#
# 客户端发现 未注册 , 跳转到邀请页面
# ( 可输入邀请码 )
#
# 输入邀请码 , 进行注册
# ( 特殊邀请码: admin )
#
# 服务端 开始初始化
# 创建 超级管理员
# 创建 测试用project
#
# ## 初始化完成 ##
#
# #######################
# 超级管理员 :
# # 创建 工作project
# # 邀请成员
# # 添加 系统管理员
# # 移去一个 系统管理员
#
# 系统管理员 :
# # 修改 project名称
#
# 管理员 :
# # 修改 分组 名称
# # 修改 分组成员 权限
# # 屏蔽帖子
#
# 成员 :
# # 发出邀请
# # 发出帖子
# # 跟帖 图片
# # 跟帖 文字
# # 关闭 帖子 ( 自己的帖子 )
# # 更换 '项目.分组' 登录
#
#
include_once '/tools/sys.php';
include_once "/class/cla_project.php";
include_once "/class/cla_user.php";
include_once "/class/cla_openid.php";
include_once "/class/set_session.php";

class BEGIN
{

    public static function 清空数据()
    {
        $a = &SYS::$DBNL;
        SDB::exec('DELETE FROM ' . $a['val']);
        SDB::exec('DELETE FROM ' . $a['user']);
        SDB::exec('DELETE FROM ' . $a['pro']);
        SDB::exec('DELETE FROM ' . $a['work']);
        SDB::exec('DELETE FROM ' . $a['openid']);

    }

    ###########################
    # 新建超级管理员 , 及其他
    #
    #
    public static function 新建超级管理员($name)
    {

        #
        # 创建 user ( 包括 openid )
        #
        # 创建 临时项目1
        #
        #  创建 临时项目2
        #
        # 创建 系统数据 ( SYS )
        # 记录 '超级管理员'的UID
        #

        SYS::初始化JSON();

        $j1 = cla_project::newProject('测试项目1');
        $j2 = cla_project::newProject('测试项目2');

        $分组 = '监理';

        $u = cla_user::newOne(
            $name,
            $j1->getJID(),
            $分组
        );
        $UID = $u->getUID();

        $o = cla_openid::newOne(
            SYS::$adminOpenID,
            $UID
        );

        $j1->被邀请进入分组(
            $UID,
            $name,
            $分组,
            $UID
        );

        Session::set($u, SYS::$adminOpenID);

        ####################################
        # 加入 '测试项目2'
        #
        $u->加入项目($j2->getJID());
        $j2->被邀请进入分组(
            $UID,
            $name,
            $分组,
            $UID
        );

    }
}
