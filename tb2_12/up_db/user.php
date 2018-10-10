<?php
//
// 把 tb2_user 分开成 <孩子> 和 <家长> 两个表
//
include_once SYS::$filePath['up_each'];

class up_user extends up_each
{

    public function _DB_del()
    {
        return ['tb3_user_h'
            , 'tb3_user_c'];
    }

    public function _DB()
    {
        return 'tb2_user';
    }

    public function each($dat)
    {

        if ($dat['JSON']['角色'] == '孩子') {
            unset($dat['JSON']['角色']);
            unset($dat['JSON']['执行包']);
            SDB::insert([
                'name' => 'tb3_user_c',
                'DAT'  => $dat,
            ]);
        } else {
            unset($dat['JSON']['执行包']);
            // unset($dat['JSON']['角色']);
            SDB::insert([
                'name' => 'tb3_user_h',
                'DAT'  => $dat,
            ]);
        }
    }

}
