<?php
// 设置返回json格式数据
header('content-type:application/json;charset=utf8');

// $r = '/^\d{1,10}$/';
// $r = '/^[\w\u4e00-\u9fa5]{2,8}$/';
$r = '/^[\w\x{4e00}-\x{9fa5}]{2,8}$/u';
$s = '炸弹';

if (!preg_match($r, $s)) {
    print_r('isNot');
} else {
    print_r('isOK');
}

