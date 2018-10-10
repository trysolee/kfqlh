<?php
include_once 'tools/sys.php';

$_SESSION['JID'] = 14;

SYS::KK('locking', '...mm1k');

// mysql_query("SET AUTOCOMMIT=0");
// KEYs::lock('家庭');
KEYs::家庭();

mysql_query("update `projoct` set name='try118a' where JID = 118");

SYS::KK('lockOK', ' ');

KEYs::free();

SYS::KK('free', ' ');
