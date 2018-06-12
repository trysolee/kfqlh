<?php

$例子 = [
    ['UID', 'ID', true], // true : 必要
    ['NA', '用户名', true],
];

$FUN = [
    'ID'   => function ($v) {
        return [
            'ERR' => false,
            'MSG' => '',
        ];
    },
    '用户名'  => function ($v) {
        return [
            'ERR' => false,
            'MSG' => '',
        ];
    },
    '昵称'   => function ($v) {
        return [
            'ERR' => false,
            'MSG' => '',
        ];
    },
    'code' => function ($v) {
        return [
            'ERR' => false,
            'MSG' => '',
        ];
    },
    '年级'   => function ($v) {
        return [
            'ERR' => false,
            'MSG' => '',
        ];
    },
];

class INPUT
{

    public static $FUN;

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

            $f = INPUT::$FUN;
            $b = $f[$v[1]]($_POST[$v[0]]);
            if ($b['ERR']) {
                $GLOBALS['RET']->错误终止_end($b['MSG']);
                exit();
            }
        }

    }
}

INPUT::$FUN = $FUN;
