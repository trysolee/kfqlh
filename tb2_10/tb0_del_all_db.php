
<?php

//
// <调试>用
//
// 清空所有<数据库>
//
include_once 'tools/sys.php';

include_once SYS::$filePath['sdb'];

SYS::if没测试_end();

foreach (SYS::$DBNL as $v) {
    SDB::exec('DELETE FROM ' . $v);
}
