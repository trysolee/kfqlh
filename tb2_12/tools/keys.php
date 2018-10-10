<?php

class KEYs
{
    public static $事务中 = false;

    public static function lock()
    {
        if (!KEYs::$事务中) {
            mysql_query("BEGIN");
            KEYs::$事务中 = true;
        }
    }

    public static function 家庭() // 必须InnoDB
    {
        KEYs::lock();

        $s = 'UPDATE `' . SYS::$DBNL['family']
            . '` set _lock=1'
            . ' WHERE JID = ' . $_SESSION['JID'];
        mysql_query($s);
    }

    public static function free()
    {
        if (KEYs::$事务中) {
            mysql_query("COMMIT");
            KEYs::$事务中 = false;
        }
    }

    public static function rollback()
    {
        if (KEYs::$事务中) {
            mysql_query("ROLLBACK");
            KEYs::$事务中 = false;
        }
    }
}
