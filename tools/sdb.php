<?php

class SDB
{
    private static $ready = false;

    private static $URL   = 'localhost';
    private static $admin = 'root';
    private static $pw    = 'root';
    private static $table = 'kfqlh';

    public static $dateTimeName =
        ['FT', 'CT', 'LT'];

    public static $notFind;

    public static $insertID;

    #=====================================
    # 初始化
    #
    private static function bSet()
    {

        $URL   = SDB::$URL;
        $admin = SDB::$admin;
        $pw    = SDB::$pw;
        $table = SDB::$table;

        //连接数据库
        $link = mysql_connect($URL, $admin, $pw) or die("Unable to connect to the MySQL!");

        mysql_query("SET NAMES 'UTF8'");

        mysql_select_db($table, $link) or die("Unable to connect to the MySQL!");

        SDB::$ready = true;
    }

    #=====================================
    # 执行 SQL
    #
    public static function SQL($sql)
    {

        if (!SDB::$ready) {
            SDB::bSet();
        }

        $result = mysql_query($sql);

        // $results = array();
        if ($row = mysql_fetch_assoc($result)) {

            // print('has ');

            if (array_key_exists('JSON', $row)) {
                $row['JSON'] = json_decode($row['JSON'], true);
            }

            SDB::$notFind = false;

            // $row['JSON'] = json_decode($row['JSON'], true);

            return $row;
        }

        SDB::$notFind = true;

    }

    #=====================================
    #
    #
    public static function insert($dat)
    {

        if (!SDB::$ready) {
            SDB::bSet();
        }

        $n = ' ';
        $v = ' ';
        $d = $dat['DAT'];
        $k = array_keys($d);
        $i = ''; // 第一个参数为 '' , 其他参数为','

        // print_r($k);

        foreach ($k as $a) {

            if (in_array($a, SDB::$dateTimeName)) {
                $n .= $i . "`" . $a . "`";
                $v .= $i . "'" . date('Y-m-d H:i:s', $d[$a]) . "'";
            } elseif ($a == 'JSON') {
                $n .= $i . "`" . $a . "`";
                $v .= $i . "'" . json_encode($d[$a], JSON_UNESCAPED_UNICODE) . "'";
            } else {
                $n .= $i . "`" . $a . "`";
                $v .= $i . "'" . $d[$a] . "'";
            }

            $i = ',';
        }

        $sql = 'insert into `' . $dat['name'] . '` (' . $n . ')VALUES (' . $v . ')';

        mysql_query($sql);
        SDB::$insertID = mysql_insert_id();

        //
        // echo $sql;
    }

    #=====================================
    #
    #
    public static function update($dat)
    {

        if (!SDB::$ready) {
            SDB::bSet();
        }

        // update projoct set JSON = '456' where JID = 2

        $n = ' ';
        $d = $dat['DAT'];
        $k = array_keys($d);
        $i = ''; // 第一个参数为 '' , 其他参数为','

        // print_r($k);

        foreach ($k as $a) {

            if (in_array($a, SDB::$dateTimeName)) {
                $n .= $i . $a . "='" . date('Y-m-d H:i:s', $d[$a]) . "'";
                // $v .= $i . "'" . date('Y-m-d H:i:s', $d[$a]) . "'";
            } elseif ($a == 'JSON') {
                $n .= $i . $a . "='" . json_encode($d[$a], JSON_UNESCAPED_UNICODE) . "'";
                // $n .= $i ."`" . $a . "`";
                // $v .= $i . "'" . json_encode($d[$a], JSON_UNESCAPED_UNICODE) . "'";
            } else {
                $n .= $i . $a . "='" . $d[$a] . "'";
                // $n .= $i . "`" . $a . "`";
                // $v .= $i . "'" . $d[$a] . "'";
            }

            $i = ',';
        }

        $w = ' ';

        if (!empty($dat['WHERE'])) {

            $d = $dat['WHERE'];
            $k = array_keys($d);
            $i = ' ';

            foreach ($k as $a) {
                $w .= $i . $a . "='" . $d[$a] . "'";
                $i = ' and ';
            }

        } elseif (!empty($dat['SQLWhere'])) {
            $w = $dat['SQLWhere'];

        } else {
            return;

        }

        $sql = 'update `' . $dat['name'] . '` set ' . $n . ' where ' . $w;

        mysql_query($sql);
        //
        // echo $sql;
    }

}
