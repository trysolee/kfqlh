<?php

/**
 *
 */
class ret
{

    private static $URL   = 'localhost';
    private static $admin = 'root';
    private static $pw    = 'root';

    private static $table = 'kfqlh';

    // private $UID   = 1;
    // private $LT    = '2018-01-10'; // 这个来自 sessio ; 用户最后登陆的时间
    // private $day10 = '2018-01-01'; // 当天 的 10天前
    // private $role  = ['管理员', '巡查'];

    private $STEP = [];
    private $DAT  = [];
    private $OPT  = [];

    #####################################
    # 如果 有不明错误 , 只返回 $OPT
    #
    private $不明错误 = false;

    public function ret()
    {
        // $this->RET['DAT'] = $this->DAT;
        // $this->RET['OPT'] = $this->OPT;
    }

    private function SQL同一个JID()
    {
        $UID    = $_SESSION["UID"];
        $LT     = $_SESSION["LT"];
        $day10  = $_SESSION["day10"];
        $role   = $_SESSION["role"];
        $JID    = $_SESSION["JID"];
        $分组 = $_SESSION["分组"];

        $arr   = [];
        $arr[] = [
            'sql' => "SELECT *"
            . " FROM  pro_work "
            . " WHERE JID = " . $JID
            . " AND FT > '" . $LT . "'"
            . " AND CT > '" . $day10 . "'"
            . " LIMIT 0 , 30",
        ];

        $j = cla_project::getByID($JID);

        $arr[] = [
            'sql' => "SELECT *"
            . " FROM  pro_work "
            . " WHERE JID = " . $JID
            . " AND FT > '" . $LT . "'"
            . " AND CT > '" . $day10 . "'"
            . " LIMIT 0 , 30",
        ];

    }
    private function SQL换了JID()
    {

    }
    private function SQL()
    {

        // $UID   = $this->UID;
        // $LT    = $this->LT;
        // $day10 = $this->day10;
        // $role  = $this->role;

        $UID    = $_SESSION["UID"];
        $LT     = $_SESSION["LT"];
        $day10  = $_SESSION["day10"];
        $role   = $_SESSION["role"];
        $JID    = $_SESSION["JID"];
        $分组 = $_SESSION["分组"];

        // 如果 换了 项目 , 需要重新上传数据
        $上次JID = $_SESSION["上次JID"];

        $arr   = [];
        $arr[] = [
            'id'   => 'pro_work',
            'name' => 'pro_work',
            'step' => ['user'], // 跳过 指定的<id>
            'role' => [], // 0 : 无限制
            'sql'  => "SELECT *"
            . " FROM  pro_work "
            . " WHERE JID = " . $JID
            . " AND FT > '" . $LT . "'"
            . " AND CT > '" . $day10 . "'"
            . " LIMIT 0 , 30",
        ];

        return [
            [
                'id'   => 'pro_work',
                'name' => 'pro_work',
                'step' => ['user'], // 跳过 指定的<id>
                'role' => [], // 0 : 无限制
                'sql'  => "SELECT *"
                . " FROM  pro_work "
                . " WHERE JID = " . $JID
                . " AND FT > '" . $LT . "'"
                . " AND CT > '" . $day10 . "'"
                . " LIMIT 0 , 30",
            ], [
                'id'   => 'user',
                'name' => 'user',
                'step' => [],
                'role' => [], // 0 : 无限制
                'sql'  => "SELECT DISTINCT c.*  FROM  "
                . "pro_user as a , pro_user as b , user as c  "
                . "where a.UID = " . $UID . "  "
                . "and a.JID = b.JID  "
                . "and b.UID = c.UID  "
                . "and c.FT > '" . $LT . "'"
                . " LIMIT 0, 30 ",
            ], [
                'id'   => 'projoct',
                'name' => 'projoct',
                'step' => [],
                'role' => [], // 0 : 无限制
                'sql'  => "SELECT b.* "
                . " FROM pro_user as a , projoct as b   "
                . " where a.UID = " . $UID . "  "
                . " and a.JID = b.JID  "
                . " and c.FT > '" . $LT . "'"
                . " LIMIT 0, 30 ",
            ], [
                'id'   => 'pro_user',
                'name' => 'pro_user',
                'step' => [],
                'role' => [], // 0 : 无限制
                'sql'  => "SELECT b.*"
                . " FROM  pro_user as a , pro_user as b , projoct as c  "
                . " where a.UID = " . $UID . "  "
                . " and a.JID = b.JID  "
                . " and a.JID = c.JID  "
                . " and c.FT > '" . $LT . "'"
                . " LIMIT 0, 30 ",
            ],
        ];
    }

// 判断 两个 Array 有没有重合的元素
    private function exists($a, $b)
    {
        if (count(array_intersect($a, $b)) > 0) {
            return true;
        }

        return false;
    }

    private function select($name, $sql)
    {

// 查询数据到数组中
        // $result = mysql_query("select UID ,name ,phone  from user ");
        $result = mysql_query($sql);

        $results = array();
        while ($row = mysql_fetch_assoc($result)) {

            if (array_key_exists('JSON', $row)) {
                $row['JSON'] = json_decode($row['JSON'], true);
            }

            // $row['JSON'] = json_decode($row['JSON'], true);

            $results[] = $row;
        }

        $this->DAT[] = [
            'name' => $name,
            'arr'  => $results,
        ];

        // 关闭连接
        mysql_free_result($result);

    }

    public function getDAT()
    {
        // 设置返回json格式数据
        header('content-type:application/json;charset=utf8');

        //连接数据库
        $link = mysql_connect(ret::$URL, ret::$admin, ret::$pw) or die("Unable to connect to the MySQL!");

        mysql_query("SET NAMES 'UTF8'");

        mysql_select_db(ret::$table, $link) or die("Unable to connect to the MySQL!");

        foreach ($this->SQL() as $value) {

            if (in_array($value['id'], $this->STEP)) {
                continue;
            }

            if ($this->exists($value['role'], $this->role)) {
                $this->select($value['name'], $value['sql']);

                $this->STEP = array_unique(array_merge($this->STEP, $value['step']));
            }
        }

        mysql_close($link);

        $_SESSION["user"]['LT'] = date('Y-m-d H:i:s');

    }

    public function setOPT($name = '', $val)
    {
        $this->OPT[$name] = $val;
    }

    public function toStr_end()
    {

        #=====================================
        #  标记最后连接的时间
        #
        $_SESSION['LT'] = strtotime('now');

        $RET = [
            'OPT' => $this->OPT,
            'DAT' => $this->DAT,
        ];
        // 将数组转成json格式
        echo json_encode($RET, JSON_UNESCAPED_UNICODE);
        //
    }

    public function 还没注册_end()
    {
        # TODO
    }

    ################################
    # 上传前 , 本地都会检查一次 ,
    # 一般不可能再出现错误 ,
    # 所以 , 属于 '不明错误'
    #
    public function 参数不全_end()
    {
        # TODO
        #
        # 属于 '不明错误'
        #
    }

    ################################
    #
    # 权限数据都会传送到本地,
    # 客户端也会根据 '权限数据' 操作
    # 如果发出 没有权限的操作 ,
    # 属于 '不明错误'
    #
    # 有可能 不是 超级管理员 或者 系统管理员
    #
    public function 不是管理员_end()
    {
        # TODO
        #
        # 属于 '不明错误'
        #
    }

    ################################
    # 微信 code 解析失败
    #
    public function codeERR_end()
    {
        # TODO
        #
        # 属于 '不明错误'
        #
    }

    ################################
    # 数据库 id select 无效
    #
    public function ID无效_end($表名)
    {
        # TODO
        #g
        #
    }

    ################################
    # 在 返回数据里面 加入 '邀请码' 信息
    #
    public function 返回邀请码($INID)
    {
        # TODO
        #
        #
    }

    ################################
    #
    #
    public function 错误终止_end($TXT)
    {
        # TODO
        #
        #
    }
}

// 设置返回json格式数据
header('content-type:application/json;charset=utf8');

$RET = $GLOBALS['RET'] = new ret();

session_start();
