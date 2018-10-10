<?php
include_once 'tools/sys.php';

$_SESSION['JID'] = 14;

SYS::KK('locking', '...mm1y');

// mysql_query("SET AUTOCOMMIT=0");
// KEYs::lock('家庭');
KEYs::家庭();

mysql_query("update `projoct` set name='god123a' where JID = 119");
SYS::KK('lockOK', '等10秒');

sleep(10);


KEYs::free();

SYS::KK('free', ' ');

RET::ret_buf_min('成员_重置' , 2);
RET::toStr_end();
