<?php

class UserModel
{
    protected $_table = "book_user";
    protected $_index = "username";

    public function __construct()
    {
        $this->_db = Yaf_Registry::get('_db');
    }

    public function loginUser($username, $password)
    {
        $params = array(
            "user_uuid",
            "password",
            "email",
            "create_time"
        );
        $whereis = array(
            "AND"=>array($this->_index=>$username, "is_del"=>0)
        );
        $result = $this->_db->select($this->_table, $params, $whereis);
        if($result == null)
            return false;
        elseif($result[0]['password'] == ($password))
            return $result[0]['user_uuid'];
        else
            return false;
    }
}