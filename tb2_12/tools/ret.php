<?php

include_once "tools/sys.php";

$RET_BUFs = [
    // --------------
    '家庭_重置' => [
        'name' => 'family',
        'sql'  => function ($db) {
            return 'SELECT *'
                . ' FROM ' . $db['family']
                . ' WHERE JID = ' . $_SESSION['JID'];
        },
        'type' => '重置', //更新 or 重置
        'rows' => 0, // 响应行数 , 每次select后更新
    ],
    '自己_重置' => [
        'name' => 'user_my',
        'sql'  => function ($db) {
            return 'SELECT *'
                . ' FROM ' . $db['u_h']
                . ' WHERE UID = ' . $_SESSION['UID'];
        },
        'type' => '重置', //更新 or 重置
        'rows' => 0, // 响应行数 , 每次select后更新
    ],
    // --------------
    '孩子_重置' => [
        'name' => 'uc',
        'sql'  => function ($db) {
            return 'SELECT *'
                . ' FROM  ' . $db['u_c']
                . ' WHERE JID = ' . $_SESSION['JID'];
        },
        'type' => '重置', //更新 or 重置
        'rows' => 0, // 响应行数 , 每次select 更新
    ],
    '孩子_更新' => [
        'name' => 'uc',
        'sql'  => function ($db) {
            return 'SELECT *'
                . ' FROM  ' . $db['u_c']
                . ' WHERE JID = ' . $_SESSION['JID']
                . ' AND FT > "' . $_SESSION['LT'] . '"';
        },
        'type' => '更新', //更新 or 重置
        'rows' => 0, // 响应行数 , 每次select 更新
    ],
    // --------------
    '家长_重置' => [
        'name' => 'uh',
        'sql'  => function ($db) {
            return 'SELECT *'
                . ' FROM  ' . $db['u_h']
                . ' WHERE JID = ' . $_SESSION['JID'];
        },
        'type' => '重置', //更新 or 重置
        'rows' => 0, // 响应行数 , 每次select 更新
    ],
    '家长_更新' => [
        'name' => 'uh',
        'sql'  => function ($db) {
            return 'SELECT *'
                . ' FROM  ' . $db['u_h']
                . ' WHERE JID = ' . $_SESSION['JID']
                . ' AND FT > "' . $_SESSION['LT'] . '"';
        },
        'type' => '更新', //更新 or 重置
        'rows' => 0, // 响应行数 , 每次select 更新
    ],
    // --------------
    // <好友_重置> 必须和 <成员_重置> 同时使用
    // 因为 他们的<BUF.标记>都为'user'
    //
    // 因为他们'同时使用' ,
    // 所以<好友_重置.type>可以为'更新'
    //
    '好友_重置' => [
        'name' => 'user',
        'sql'  => function ($db) {
            return 'SELECT b.* '
                . ' FROM ' . $db['friend'] . ' as a'
                . ' , ' . $db['u_c'] . ' as b'
                . ' WHERE a.JID = ' . $_SESSION['JID']
                . ' AND a.UID = b.UID';
        },
        'type' => '更新', //更新 or 重置
        'rows' => 0, // 响应行数 , 每次select 更新
    ],
    // --------------
    '好友_更新' => [ // 临时暂停
        'name' => 'user',
        'sql'  => function ($db) {
            return 'SELECT b.* '
                . ' FROM ' . $db['friend'] . ' as a'
                . ' , ' . $db['u_c'] . ' as b'
                . ' WHERE a.JID = ' . $_SESSION['JID']
                . ' AND a.UID = b.UID'
                . ' AND b.FT > "' . $_SESSION['LT'] . '"';
        },
        'type' => '更新', //更新 or 重置
        'rows' => 0, // 响应行数 , 每次select 更新
    ],
];

/**
 *
 */
class RET
{

    public static $RET_BUFs;

    private static $DAT = [];
    private static $OPT = [];

    // true : 发生错误
    // false : 正常
    private static $ERR = false;

    ################################
    #  客户端 跳转到指定的<page>
    #
    public static function toPage($p)
    {
        RET::setOPT('toPage', $p);
    }

    public static function 返回后续($n) // val

    {
        RET::addOPT('call', $n);
    }

    public static function 清空指定BUF($n)
    {
        RET::addOPT('freeBUF', $n);
    }

    // private $is登录连接 = false;
    // public static function 登录返回()
    // {
    //     RET::is登录连接 = true;
    // }

    // 判断 两个 Array 有没有重合的元素
    public static function exists($a, $b)
    {
        if (count(array_intersect($a, $b)) > 0) {
            return true;
        }
        return false;
    }

    private static $RET_ARR = [];

    public static function ret_buf($name)
    {
        RET::ret_buf_min($name, 0);
    }

    public static function ret_buf_min($name, $n)
    {
        if (array_key_exists($name, RET::$RET_ARR)) {
            if (RET::$RET_ARR[$name] < $n) {
                RET::$RET_ARR[$name] = $n;
            }
        } else {
            RET::$RET_ARR[$name] = $n;
        }
    }

    public static function ret_buf_go()
    {

        if (RET::$ERR) {
            return;
        }
        $db = &SYS::$DBNL;
        foreach (RET::$RET_ARR as $key => $value) {
            $r = &RET::$RET_BUFs[$key];
            // SYS::KK('getName', $r);

            $f = &$r['sql'];

            $result = mysql_query($f($db));
            if ($result) {

                $len = mysql_num_rows($result);
                if ($len < $value) {
                    mysql_free_result($result);
                    RET::错误终止_end('响应行数不足.' . $key);
                }

                $results = array();
                while ($row = mysql_fetch_assoc($result)) {

                    if (array_key_exists('JSON', $row)) {
                        $row['JSON'] = json_decode($row['JSON'], true);
                    }

                    $results[] = $row;
                }
                RET::$DAT[] = [
                    'name' => $r['name'],
                    'arr'  => $results,
                ];

                if ($r['type'] == '重置') {
                    RET::清空指定BUF($r['name']);
                }

            } else {
                if ($value > 0) {
                    mysql_free_result($result);
                    RET::错误终止_end('响应行数不足.' . $key);
                }

            }
        }

        // 关闭连接
        if (!empty($result)) {
            mysql_free_result($result);
        }

    }

    // public function getDAT()
    // {

    //     foreach ($this->SQL() as $value) {
    //         $this->select($value['name'], $value['sql']);
    //     }
    // }

    public static function addOPT($name, $val)
    {
        if (array_key_exists($name, RET::$OPT)) {
            RET::$OPT[$name][] = $val;
        } else {
            RET::$OPT[$name] = [$val];
        }

    }

    public static function setOPT($name, $val)
    {
        RET::$OPT[$name] = $val;
    }

    public static function toStr_onlyOPT_end()
    {

        //
        $Ret = [
            'OPT' => RET::$OPT,
            'DAT' => [],
        ];
        // 关闭 mySQL
        SDB::_END();

        // 将数组转成json格式
        echo json_encode($Ret, JSON_UNESCAPED_UNICODE);
        //
        exit();
    }

    public static function toStr_end()
    {

        ###################
        # 如果 没有错误 , save
        #
        if (!RET::$ERR) {
            SYS::BUF_save();
            RET::ret_buf_go();

            // 
            RET::setOPT('CS版本', SYS::$本地数据版本);
        }
        // 关闭 mySQL
        SDB::_END();
        //
        $Ret = [
            'OPT' => RET::$OPT,
            'DAT' => RET::$DAT,
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

    public static function 还没注册_end()
    {
        RET::$ERR = true;

        RET::setOPT('ERR', '90');
        RET::setOPT('MSG', '还没注册');

        RET::返回后续('还没注册');
        RET::toStr_onlyOPT_end(); // 自带 exit()

    }

    ################################
    # 上传前 , 本地都会检查一次 ,
    # 一般不可能再出现错误 ,
    # 所以 , 属于 '不明错误'
    #
    public static function 参数不全_end()
    {
        RET::$ERR = true;

        RET::setOPT('ERR', '90');
        RET::setOPT('MSG', '参数不全');

        RET::toStr_onlyOPT_end();
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
    public static function 不是管理员_end()
    {
        RET::$ERR = true;

        RET::setOPT('ERR', '90');
        RET::setOPT('MSG', '权限错误');

        RET::toStr_onlyOPT_end();
    }

    ################################
    # 微信 code 解析失败
    #
    public static function codeERR_end()
    {
        // 就是 code 无法转换成 openid
        RET::$ERR = true;

        RET::setOPT('ERR', '90');
        RET::setOPT('MSG', '微信登录失败');

        RET::toStr_onlyOPT_end();
    }

    ################################
    # 数据库 id select 无效
    #
    public static function ID无效_end($表名)
    {
        RET::$ERR = true;

        RET::setOPT('ERR', '90');
        RET::setOPT('MSG', 'ID无效');

        RET::toStr_onlyOPT_end();
    }

    ################################
    # 在 返回数据里面 加入 '邀请码' 信息
    #
    public static function 返回邀请码($INID)
    {
        RET::setOPT('INID', $INID);
    }

    ################################
    #  返回 session_id
    #
    public static function 返回session_id()
    {
        RET::setOPT('_SID', session_id());
    }

    ################################
    #
    #
    public static function 错误终止_end($TXT)
    {
        RET::$ERR = true;

        RET::setOPT('ERR', '93');
        RET::setOPT('MSG', $TXT);

        RET::toStr_onlyOPT_end();
    }

    public static function 不在项目_end()
    {
        RET::$ERR = true;

        RET::setOPT('ERR', '95');
        RET::setOPT('MSG', '需要重新接收邀请');

        RET::toStr_onlyOPT_end();
    }
}

// 设置返回json格式数据
if (SYS::$打开KK) {
    header('content-type:text/html;charset=utf8');

    ob_end_clean();
    ob_implicit_flush(1);
    // header('content-type:application/json;charset=utf8');
} else {
    header('content-type:application/json;charset=utf8');
}

RET::$RET_BUFs = $RET_BUFs;
// $RET           = $GLOBALS['RET']           = new ret();

if (!empty($_POST['_SID'])) {
    session_id($_POST['_SID']);
}

session_start();
