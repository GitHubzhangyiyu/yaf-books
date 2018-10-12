<?php

Class AdminModel
{
    protected $_table = 'book_admin';
    protected $_index = 'username';

    public function __construct()
    {
        $this->_db = Yaf_Registry::get('_db');
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     * 用户登录
     */
    public function loginUser($username, $password)
    {
        $params = array(
            'password',
            'email',
            'create_time'
        );
        $whereis = array(
            'AND'=>array($this->_index=>$username, 'is_del'=>0)
        );
        $result = $this->_db->select($this->_table, $params, $whereis);
        if($result == null)    //用户名找不到返回false
        {
            return false;
        }
        elseif($result[0]['password'] == ($password))    //用户名找到且密码匹配返回true
        {
            return true;
        }
        else    //密码不匹配也返回false
        {
            return false;
        }
    }

    /**
     * @param $info
     * @return bool
     * 新增管理员；或者修改已经存在的管理员密码，邮箱等信息
     */
    public function insert($info)
    {
        $sql = "REPLACE INTO ".$this->_table."(username, email, password, is_del) VALUES('".$info['username']."', '".$info['email']."', '".$info['password']."', b'0');";
        $result = $this->_db->exec($sql);
        return $result<1?false:true;
    }
}