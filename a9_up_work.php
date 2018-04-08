<?php
###############################
#  工作贴
#
#  后续 上传图片 , 或上传文字
#

include_once "/tools/ret.php";
include_once "class/cla_in.php";

###############################
# 参数检查
#
SYS::参数检查_end(['WID']);

$w = cla_work::getByID($_POST['WID']);

$w->检查_上传文件('file');

$w->检查_上传TXT();

###############################
# 结束返回
#
$RET->toStr_end();
