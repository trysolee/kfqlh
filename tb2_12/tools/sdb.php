<?php

include_once 'tools/sdb_one.php';

class SDB
{
    private static $local = true; // 本地数据库
    private static $ready = false; // 初始化标记

    private static $URL   = 'localhost';
    private static $admin = 'root';
    private static $pw    = 'root';
    private static $table = 'kfqlh';

    private static $_URL   = 'sqld-gz.bcehost.com';
    private static $_post  = '3306'; // 默认端口
    private static $_admin = 'bd160c68b0dd42eab1975d8ae403495f';
    private static $_pw    = 'e422d14c4bf74932ac7c6f470e321596';
    private static $_table = 'zanZHPfBNBZgvZgraebU';

    private static $link;

    public static $dateTimeName =
        ['FT', 'CT', 'LT'];

    public static $notFind;

    public static $insertID;

    #=====================================
    # 初始化
    #
    public static function bSet()
    {

        if (SDB::$ready) {
            return;
        }

        ini_set('display_errors', true);
        error_reporting(E_ALL);

        if (SDB::$local) {
            $URL   = SDB::$URL;
            $admin = SDB::$admin;
            $pw    = SDB::$pw;
            $table = SDB::$table;
        } else {
            $URL   = SDB::$_URL . ':' . SDB::$_post;
            $admin = SDB::$_admin;
            $pw    = SDB::$_pw;
            $table = SDB::$_table;
        }

        //连接数据库
        SDB::$link = $link = mysql_connect($URL, $admin, $pw) or die("Unable to connect to the MySQL!");

        mysql_query("SET NAMES 'UTF8'");

        mysql_select_db($table, $link) or die("Unable to connect to the MySQL!");

        SDB::$ready = true;
    }

    #=====================================
    # 结束 关闭
    #
    public static function _END()
    {
        if (SDB::$ready) {
            mysql_close(SDB::$link);
            SDB::$ready = false;
        }
    }

    public static function exec($sql)
    {
        if (!SDB::$ready) {
            SDB::bSet();
        }

        // mysql_query($sql);

        if (!mysql_query($sql)) {
            SYS::KK('Exec 失败', $sql);
        }
    }

    #=====================================
    # 执行 SQL
    #
    public static function SQL($sql)
    {

        SDB::$notFind = true;

        if (!SDB::$ready) {
            SDB::bSet();
        }

        $result = mysql_query($sql);

        if ($result) {

            if ($row = mysql_fetch_assoc($result)) {

                if (array_key_exists('JSON', $row)) {
                    $row['JSON'] = json_decode($row['JSON'], true);
                }
                SDB::$notFind = false;
                // $row['JSON'] = json_decode($row['JSON'], true);

                return $row;
            }
        } else {

            RET::错误终止_end('SDB 1 ' . $sql);

        }

    }

    // 返回多行数据
    public static function SQL_s($sql)
    {

        if (!SDB::$ready) {
            SDB::bSet();
        }

        $result = mysql_query($sql);
        if ($result) {

            $arr = [];
            while ($row = mysql_fetch_assoc($result)) {

                if (array_key_exists('JSON', $row)) {
                    $row['JSON'] = json_decode($row['JSON'], true);
                }

                $arr[] = $row;
            }

            return $arr;

        } else {

            RET::错误终止_end('SDB 2 ' . $sql);

        }

        SDB::$notFind = true;

    }

    //
    public static function SQL_each($sql, $each)
    {

        if (!SDB::$ready) {
            SDB::bSet();
        }

        $result = mysql_query($sql);
        if ($result) {
            while ($row = mysql_fetch_assoc($result)) {

                if (array_key_exists('JSON', $row)) {
                    $row['JSON'] = json_decode($row['JSON'], true);
                }
                $each->each($row);
            }

        } else {

            RET::错误终止_end('SDB 3 ' . $sql);

        }
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

            // if (in_array($a, SDB::$dateTimeName)) {
            //     $n .= $i . "`" . $a . "`";
            //     $v .= $i . "'" . date('Y-m-d H:i:s', $d[$a]) . "'";
            // } elseif ($a == 'JSON') {

            if ($a == 'JSON') {
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

            // if (in_array($a, SDB::$dateTimeName)) {
            //     $n .= $i . $a . "='" . date('Y-m-d H:i:s', $d[$a]) . "'";
            //     // $v .= $i . "'" . date('Y-m-d H:i:s', $d[$a]) . "'";
            // } elseif ($a == 'JSON') {

            if ($a == 'JSON') {
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

SDB::bSet();
