<?php

$例子 = [
    ['UID', 'ID', true], // true : 必要
    ['NA', '用户名', true],
];

$INPUT_FUN = [
    'ID'   => function ($v) {
        return [
            'ERR' => !preg_match(INPUT::$REG['ID'], $v),
            'MSG' => $v . '_输入错误1',
        ];
    },
    '用户名'  => function ($v) {
        return [
            'ERR' => !preg_match(INPUT::$REG['用户名'], $v),
            'MSG' => $v . '_输入错误2',
        ];
    },
    '昵称'   => function ($v) {
        return [
            'ERR' => !preg_match(INPUT::$REG['昵称'], $v),
            'MSG' => $v . '_输入错误3',
        ];
    },
    'code' => function ($v) {
        return [
            'ERR' => false,
            'MSG' => '',
        ];
    },
    '年级'   => function ($v) {
        $r = [
            'ERR' => false,
            'MSG' => $v . '_输入错误4',
        ];
        if (!preg_match(INPUT::$REG['ID'], $v)) {
            $r['ERR'] = true;
            return $r;
        };
        if ($v < 3) {
            $r['ERR'] = true;
        };
        if ($v > 12) {
            $r['ERR'] = true;
        };
        return $r;
    },
    '数值'   => function ($v) {
        return [
            'ERR' => !preg_match(INPUT::$REG['实数'], $v),
            'MSG' => $v . '_输入错误5',
        ];
    },
    //
    '短密'   => function ($v) {
        return [
            'ERR' => !preg_match(INPUT::$REG['短密'], $v),
            'MSG' => $v . '_输入错误6',
        ];
    },
    //
    '邀请码'  => function ($v) {
        return [
            'ERR' => !preg_match(INPUT::$REG['邀请码'], $v),
            'MSG' => $v . '_输入错误7',
        ];
    },
];

class INPUT
{

    public static $FUN;
    public static $REG;

    ##############################
    #
    public static function 参数检查_end($arr)
    {

        foreach ($arr as $v) {
            //
            // 如果 参数缺失
            // 而参数又是必要的
            // 报异常 < 参数不全 >
            //
            // 如果参数缺失
            // 但参数不是必要的
            // 则 continue
            //

            if (empty($_POST[$v[0]])) {
                if ($v[2]) {
                    $GLOBALS['RET']->参数不全_end();
                    exit();
                }
                continue;
            }
            //
            // 如果 没有$v[1]
            // 立即 continue
            //
            if (empty($v[1])) {
                continue;
            }

            // 异常
            // $b = $INPUT_FUN[$v[1]]($_POST[$v[0]]);
            //
            // 改成下面的形式
            $f = INPUT::$FUN;
            $b = $f[$v[1]]($_POST[$v[0]]);
            //
            if ($b['ERR']) {
                $GLOBALS['RET']->错误终止_end($b['MSG']);
                exit();
            }
        }

    }
}

INPUT::$FUN = $INPUT_FUN;
INPUT::$REG = [
    'ID'  => '/^\d{1,10}$/',
    '实数'  => '/^(-?\d+)(\.\d+)?$/',
    '整数'  => '/^-?\d+$/',
    '昵称'  => '/^[\w\x{4e00}-\x{9fa5}]{2,8}$/u',
    '用户名' => '/^[a-zA-Z][a-zA-Z0-9]{3,15}$/',
    '邀请码' => '/^\d{6}$/', // 6位数
    '短密'  => '/^\d{3}$/', // 3位数

];
