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

    private $返回user数据 = false;

    // 返回 <项目.分组>的全部user
    // 用于 <user>权限调整
    private $返回pro的全部user数据 = false;

    #####################################
    # 如果 有不明错误 , 只返回 $OPT
    #
    private $不明错误 = false;

    public function ret()
    {
        // $this->RET['DAT'] = $this->DAT;
        // $this->RET['OPT'] = $this->OPT;
    }

    public function 返回后续($n)
    {
        $this->addOPT('call', $n);
    }
    public function 清空指定BUF($n)
    {
        $this->addOPT('freeBUF', $n);
    }

    public function 登录返回()
    {
        $this->返回user数据 = true;
    }

    public function 返回pro的全部user()
    {
        $this->返回pro的全部user数据 = true;
    }

    private function SQL同一个JID()
    {
        $UID   = $_SESSION["UID"];
        $LT    = $_SESSION["LT"];
        $day10 = $_SESSION["day10"];
        // $role   = $_SESSION["role"];
        $JID    = $_SESSION["JID"];
        $分组 = $_SESSION["分组"];
        $S      = &SYS::$DBNL;

        // SYS::KK('copy $DBNL', $S);

        $arr = [];

        // 返回 所有相关的 <项目>
        // 考虑 <项目.分组.名称> 需要再<客户端>显示
        //
        $arr[] = [
            'name' => 'projoct',

            'sql'  => "SELECT distinct b.*"
            . " FROM  " . $S['pro_user'] . " as a"
            . " ," . $S['pro'] . " as b"
            . " WHERE a.UID = " . $UID
            . " AND a.JID = b.JID"
            . " AND b.FT > '" . $LT . "'",
        ];

        $arr[] = [
            'name' => 'pro_user',

            'sql'  => "SELECT * "
            . " FROM  " . $S['pro_user']
            . " WHERE UID = " . $UID
            . " AND FT > '" . $LT . "'",
        ];

        $arr[] = [
            'name' => 'work',

            'sql'  => "SELECT *"
            . " FROM  " . $S['work']
            . " WHERE JID = " . $JID
            . " AND FT > '" . $LT . "'"
            . " AND CT > '" . $day10 . "'"
            . " LIMIT 0 , 30",
        ];
        return $arr;
    }
    private function SQL换了JID()
    {
        $UID   = $_SESSION["UID"];
        $LT    = $_SESSION["LT"];
        $day10 = $_SESSION["day10"];
        // $role   = $_SESSION["role"];
        $JID    = $_SESSION["JID"];
        $分组 = $_SESSION["分组"];
        $S      = &SYS::$DBNL;

        // SYS::KK('copy $DBNL', $S);

        $arr = [];

        // 返回 所有相关的 <项目>
        // 考虑 <项目.分组.名称> 需要再<客户端>显示
        //
        $arr[] = [
            'name' => 'projoct',

            'sql'  => "SELECT distinct b.*"
            . " FROM  " . $S['pro_user'] . " as a"
            . " ," . $S['pro'] . " as b"
            . " WHERE a.UID = " . $UID
            . " AND a.JID = b.JID",
        ];

        $arr[] = [
            'name' => 'pro_user',

            'sql'  => "SELECT * "
            . " FROM  " . $S['pro_user']
            . " WHERE UID = " . $UID,
        ];

        $arr[] = [
            'name' => 'work',

            'sql'  => "SELECT *"
            . " FROM  " . $S['work']
            . " WHERE JID = " . $JID
            . " AND CT > '" . $day10 . "'",
        ];

        return $arr;
    }
    private function SQL()
    {
        if ($_SESSION['JID_LT'] == $_SESSION['JID']) {
            $a = $this->SQL同一个JID();
        } else {
            $a = $this->SQL换了JID();
        }

        if ($this->返回user数据) {
            $a[] = [
                'name' => 'user_my',

                'sql'  => "SELECT *"
                . " FROM  user "
                . " WHERE UID = " . $_SESSION["UID"],
            ];
        }

        if ($this->返回pro的全部user数据) {
            $a[] = [
                'name' => 'pro_all_user',

                'sql'  => "SELECT a.* b.name "
                . " FROM  pro_user as a "
                . " ,user as b"
                . " WHERE JID = " . $_SESSION['JID']
                . " AND group = " . $_SESSION['分组']
                . " AND a.UID = b.UID",
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
        ###################
        # 如果 没有错误 , save
        #
        if (!$this->ERR) {
            SYS::BUF_save();
            $this->getDAT();
        }
        // 关闭 mySQL
        SDB::_END();

        // 返回 登陆数据
        if ($this->返回user数据) {
            if (SYS::is超级管理员()) {
                $this->setOPT('supAdmin', 'true');
            }
            if (SYS::is系统管理员()) {
                $this->setOPT('Admin', 'true');
            }
        }
        //
        $Ret = [
            'OPT' => $this->OPT,
            'DAT' => $this->DAT,
        ];

        #=============================
        #  标记最后连接的时间
        #
        $_SESSION['LT'] = SYS::$NOW;

        if (!empty($_SESSION['JID'])) {
            $_SESSION['JID_LT'] = $_SESSION['JID'];
        }

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
        $this->toStr_end();
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
    #  客户端 跳转到指定的<page>
    #
    public function toPage($p)
    {
        $this->setOPT('toPage', $p);
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
    header('content-type:text/html;charset=utf8');
} else {
    header('content-type:application/json;charset=utf8');
}

$RET = $GLOBALS['RET'] = new ret();

if (!empty($_POST['_SID'])) {
    session_id($_POST['_SID']);
}

session_start();
