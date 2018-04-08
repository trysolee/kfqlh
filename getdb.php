<?php
/**
 *
 */
class getDB
{

// TODO
    //
    //



    private $dateTimeName = ['FT', 'LT'];

    public $notFind;

    public $insertID;

    public function getDB()
    {

        $URL   = 'localhost';
        $admin = 'root';
        $pw    = 'root';

        $table = 'kfqlh';

        //连接数据库
        $link = mysql_connect($URL, $admin, $pw) or die("Unable to connect to the MySQL!");

        mysql_query("SET NAMES 'UTF8'");

        mysql_select_db($table, $link) or die("Unable to connect to the MySQL!");
    }

    public function SQL($sql)
    {
        $result = mysql_query($sql);

        // $results = array();
        if ($row = mysql_fetch_assoc($result)) {

            // print('has ');

            if (array_key_exists('JSON', $row)) {
                $row['JSON'] = json_decode($row['JSON'], true);
            }

            $this->notFind = false;

            // $row['JSON'] = json_decode($row['JSON'], true);

            return $row;
        }

        $this->notFind = true;

    }

    public function UserByUID($uid)
    {
        # code...
        #
        # 通过 UID 建立 User 对象

        return $this->SQL('select * from user where UID = ' . $uid);
    }

    public function UserByOpenID($OID)
    {
        # code...
        #
        # 通过 OpenID 建立 User 对象
        #

        $sql = "SELECT a.* FROM user as a , openid as b"
            . " where b.openid = \"" . $OID . "\""
            . " and b.UID = a.UID";
        return $this->SQL($sql);
    }

    public function proByIN($ID)
    {

        $sql = "SELECT b.* , a.UID FROM pro_in as a , projoct as b "
            . " where a.JID = b.JID "
            . "and a.INID = " . $ID;
        return $this->SQL($sql);
    }

        public function proByID($ID)
    {

        $sql = "SELECT *  FROM  projoct  "
            . " where JID = " . $ID;
        return $this->SQL($sql);
    }

    public function insert($dat)
    {

        $n = ' ';
        $v = ' ';
        $d = $dat['DAT'];
        $k = array_keys($d);
        $i = ''; // 第一个参数为 '' , 其他参数为','

        // print_r($k);

        foreach ($k as $a) {

            if (in_array($a, $this->dateTimeName)) {
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
        $insertID = mysql_insert_id();

        //
        // echo $sql;
    }

    public function update($dat)
    {

        // update projoct set JSON = '456' where JID = 2

        $n = ' ';
        $d = $dat['DAT'];
        $k = array_keys($d);
        $i = ''; // 第一个参数为 '' , 其他参数为','

        // print_r($k);

        foreach ($k as $a) {

            if (in_array($a, $this->dateTimeName)) {
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
