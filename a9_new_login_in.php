<?php
###############################
# 发起 邀请
# 
# 邀请当前'项目.分组'
#

include_once "/tools/ret.php";
include_once "class/cla_in.php";

###############################
# 新建 邀请码
#
# 在 RET 里面记录 '邀请码' (newOne 里面有)
#
cla_in::newOne();

###############################
# 结束返回
#
$RET->toStr_end();
