<?php

class FavModel
{
    protected $_table = "book_fav";

    public function __construct()
    {
        $this->_db = Yaf_Registry::get('_db');
    }

    public function insert($info)
    {
        $result = $this->_db->insert($this->_table, $info);
        return $result<null?false:$result;
    }
}