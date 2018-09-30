<?php

// 设置返回json格式数据
header('content-type:application/json;charset=utf8');

$f = [
    'go'    => function () {
        return 'abc';
    },
    '家庭_重置' => [
        'name' => 'family',
        'sql'  => function ($db, $se) {
            // $se = $_SESSION
            return 'SELECT *'
                . ' FROM  ' . $db['family']
                . ' WHERE JID = ' . $se['JID'];
        },
        'type' => '重置', //更新 or 重置
        'rows' => 0, // 响应行数 , 每次select后更新
    ],
];

class ret
{

    private static $BUFs = [
        // --------------
        '家庭_重置' => [
            'name' => 'family',
            'sql'  => function ($db, $se) { // ERR
                // $se = $_SESSION
                return 'SELECT *'
                    . ' FROM  ' . $db['family']
                    . ' WHERE JID = ' . $se['JID'];
            },
            'type' => '重置', //更新 or 重置
            'rows' => 0, // 响应行数 , 每次select后更新
        ],

    ];
};
/**
 *
 */
class Ca
{

    public static $aa;

    public function hh()
    {

        $d = Ca::$aa;
        print_r($d['go']());
    }

}

Ca::$aa = $f;

// print_r($f['go']());
//
$d = Ca::$aa;

// print_r(Ca::$aa['go']());

// print_r($d['go']());
//
