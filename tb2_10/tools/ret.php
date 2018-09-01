<?php

include_once "tools/sys.php";

/**
 *
 */
class ret
{

    private static $URL   = 'localhost';
    private static $admin = 'root';
    private static $pw    = 'root';

    private static $table = 'kfqlh';

    private $STEP = [];
    private $DAT  = [];
    private $OPT  = [];

    // true : 发生错误
    // false : 正常
    private $ERR = false;

    #####################################
    # 如果 有不明错误 , 只返回 $OPT
    #
    private $不明错误 = false;

    public function ret()
    {
        // $this->RET['DAT'] = $this->DAT;
        // $this->RET['OPT'] = $this->OPT;
    }

    ################################
    #  客户端 跳转到指定的<page>
    #
    public function toPage($p)
    {
        $this->setOPT('toPage', $p);
    }

    public function 返回后续($n) // val

    {
        $this->addOPT('call', $n);
    }

    public function 清空指定BUF($n)
    {
        $this->addOPT('freeBUF', $n);
    }

    private $is登录连接 = false;
    public function 登录返回()
    {
        $this->is登录连接 = true;
    }

    private $SQLs = [];

    public function add_SQL($sql)
    {
        $this->SQLs[] = $sql;
    }

    private function SQL()
    {

        $UID = $_SESSION["UID"];
        $LT  = $_SESSION["LT"];

        $JID = $_SESSION["家庭ID"];
        $S   = &SYS::$DBNL;

        $a = $this->SQLs;

        if ($this->is登录连接) {
            $a[] = [
                'name' => 'family',

                'sql'  => "SELECT *"
                . " FROM  " . $S['family']
                . " WHERE JID = " . $JID,
            ];
            $a[] = [
                'name' => 'user_my',

                'sql'  => "SELECT *"
                . " FROM  " . $S['user']
                . " WHERE UID = " . $UID,
            ];

            $a[] = [
                'name' => 'user',

                'sql'  => "SELECT *"
                . " FROM  " . $S['user']
                . " WHERE JID = " . $JID,
            ];

            $a[] = [
                'name' => 'user', // friend

                'sql'  => "SELECT b.* "
                . " FROM " . $S['friend'] . ' as a'
                . ' , ' . $S['user'] . ' as b'
                . " WHERE a.JID = " . $JID
                . " AND a.UID = b.UID",
            ];
        } else {

            // 非登陆连接 , ( 平常最频繁的调用 )
            $a[] = [
                'name' => 'user',

                'sql'  => "SELECT *"
                . " FROM  " . $S['user']
                . " WHERE JID = " . $JID
                . " AND FT > '" . $LT . "'",
            ];

            $a[] = [
                'name' => 'user', // friend

                'sql'  => "SELECT b.* "
                . " FROM " . $S['friend'] . ' as a'
                . ' , ' . $S['user'] . ' as b'
                . " WHERE a.JID = " . $JID
                . " AND a.UID = b.UID"
                . " AND b.FT > '" . $LT . "'",
            ];
        }

        return $a;
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
        if (!$result) {
            SYS::KK('RET select SQL return Null', $sql);
        }

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
        // header('content-type:application/json;charset=utf8');

        // //连接数据库
        // $link = mysql_connect(ret::$URL, ret::$admin, ret::$pw) or die("Unable to connect to the MySQL!");

        // mysql_query("SET NAMES 'UTF8'");

        // mysql_select_db(ret::$table, $link) or die("Unable to connect to the MySQL!");

        foreach ($this->SQL() as $value) {
            $this->select($value['name'], $value['sql']);
        }
    }

    public function addOPT($name, $val)
    {
        if (array_key_exists($name, $this->OPT)) {
            $this->OPT[$name][] = $val;
        } else {
            $this->OPT[$name] = [$val];
        }

    }

    public function setOPT($name, $val)
    {
        $this->OPT[$name] = $val;
    }

    public function toStr_end()
    {

        SDB::bSet();

        ###################
        # 如果 没有错误 , save
        #
        if (!$this->ERR) {
            SYS::BUF_save();
            $this->getDAT();
        }
        // 关闭 mySQL
        SDB::_END();

        //
        $Ret = [
            'OPT' => $this->OPT,
            'DAT' => $this->DAT,
        ];

        #=============================
        #  标记最后连接的时间
        #
        $_SESSION['LT'] = SYS::$NOW;

        // 将数组转成json格式
        echo json_encode($Ret, JSON_UNESCAPED_UNICODE);
        //
        exit();
    }

    public function 还没注册_end()
    {
        $this->ERR = true;

        $this->setOPT('ERR', '90');
        $this->setOPT('MSG', '还没注册');

        $this->返回后续('还没注册');
        $this->toStr_end(); // 自带 exit()

    }

    ################################
    # 上传前 , 本地都会检查一次 ,
    # 一般不可能再出现错误 ,
    # 所以 , 属于 '不明错误'
    #
    public function 参数不全_end()
    {
        $this->ERR = true;

        $this->setOPT('ERR', '90');
        $this->setOPT('MSG', '参数不全');

        $this->toStr_end();
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
        $this->ERR = true;

        $this->setOPT('ERR', '90');
        $this->setOPT('MSG', '权限错误');

        $this->toStr_end();
    }

    ################################
    # 微信 code 解析失败
    #
    public function codeERR_end()
    {
        // 就是 code 无法转换成 openid
        $this->ERR = true;

        $this->setOPT('ERR', '90');
        $this->setOPT('MSG', '微信登录失败');

        $this->toStr_end();
    }

    ################################
    # 数据库 id select 无效
    #
    public function ID无效_end($表名)
    {
        $this->ERR = true;

        $this->setOPT('ERR', '90');
        $this->setOPT('MSG', 'ID无效');

        $this->toStr_end();
    }

    ################################
    # 在 返回数据里面 加入 '邀请码' 信息
    #
    public function 返回邀请码($INID)
    {
        $this->setOPT('INID', $INID);
    }

    ################################
    #  返回 session_id
    #
    public function 返回session_id()
    {
        $this->setOPT('_SID', session_id());
    }

    ################################
    #
    #
    public function 错误终止_end($TXT)
    {
        $this->ERR = true;

        $this->setOPT('ERR', '93');
        $this->setOPT('MSG', $TXT);

        $this->toStr_end();
    }

    public function 不在项目_end()
    {
        $this->ERR = true;

        $this->setOPT('ERR', '95');
        $this->setOPT('MSG', '需要重新接收邀请');

        $this->toStr_end();
    }
}

// 设置返回json格式数据
if (SYS::$调试) {
    // header('content-type:text/html;charset=utf8');

    header('content-type:application/json;charset=utf8');
} else {
    header('content-type:application/json;charset=utf8');
}

$RET = $GLOBALS['RET'] = new ret();

if (!empty($_POST['_SID'])) {
    session_id($_POST['_SID']);
}

session_start();
