<?php
###############################
# 关闭 一个 帖子
# ( 自己的帖子 或 管理员)
#
# 不能回复
#

include_once "/tools/sys.php";
include_once "/tools/ret.php";
include_once "/class/cla_work.php";

###############################
# 参数检查
#
SYS::参数检查_end(['WID']);

$w = cla_work::getByID($_POST['WID']);
$w->is当前项目_分组_end();
$w->is我发布的or分组管理员_end();

$w->closeIt();

###############################
# 结束返回
#
$RET->toStr_end();
