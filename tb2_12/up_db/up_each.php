<?php

abstract class up_each
{

    abstract public function _DB();

    abstract public function _DB_del();

    abstract public function each($dat);

    public function SQLstr()
    {
        return 'select * from `' . $this->_DB() . '`;';
    }

    public function del()
    {
        $arr = $this->_DB_del();
        foreach ($arr as $key => $value) {
            SDB::exec("delete from " . $value);
        }
    }

    public function go()
    {
        $this->del();
        SDB::SQL_each($this->SQLstr(), $this);
    }

}
