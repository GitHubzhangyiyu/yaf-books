<?php

Class AdminModel
{
    protected $_table = "book_admin";
    protected $_index = "username";

    public function __construct()
    {
        $this->_db = Yaf_Registry::get('_db');
    }

    public function loginUser($username, $password)
    {
        $params = array(
            "password",
            "email",
            "create_time"
        );
        $whereis = array(
            "AND"=>array($this->_index=>$username, "is_del"=>0)
        );
        $result = $this->_db->select($this->_table, $params, $whereis);
        if($result == null)
        {
            return false;
        }
        elseif($result[0]['password'] == ($password))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //新增管理员，或者修改管理员密码，邮箱等信息
    public function insert($info)
    {
        $sql = "REPLACE INTO ".$this->_table."(username, email, password, is_del) VALUES('".$info['username']."', '".$info['email']."', '".$info['password']."', b'0');";
        $result = $this->_db->exec($sql);
        return $result<1?false:true;
    }
}