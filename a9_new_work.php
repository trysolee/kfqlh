<?php
###############################
# 创建 工作贴
#

include_once "/tools/ret.php";
include_once "class/cla_in.php";

###############################
# 参数检查
#
SYS::参数检查_end(['WT']);

// $WT : 标签组
$WT = json_decode($_POST['WT'], true);
$w  = cla_work::newWork($WT);

$w->检查_上传文件('file');

$w->检查_上传TXT();

###############################
# 结束返回
#
$RET->toStr_end();
