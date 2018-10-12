<?php

class UserModel
{
    protected $_table = 'book_user';
    protected $_index = 'username';

    public function __construct()
    {
        $this->_db = Yaf_Registry::get('_db');
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     * 普通用户登录
     */
    public function loginUser($username, $password)
    {
        $params = array(
            'user_uuid',
            'password',
            'email',
            'create_time'
        );
        $whereis = array(
            'AND'=>array($this->_index=>$username, 'is_del'=>0)
        );
        $result = $this->_db->select($this->_table, $params, $whereis);
        if($result == null)
            return false;
        elseif($result[0]['password'] == ($password))
            return $result[0]['user_uuid'];
        else
            return false;
    }

    /**
     * @param $username
     * @return bool
     * 判断是否已经有了该用户
     */
    public function select_username($username)
    {
        $params = array(
            'user_uuid'
        );
        $whereis = array(
            'AND'=>array(
                'username'=>$username,
                'is_del'=>0
            )
        );
        $result = $this->_db->select($this->_table, $params, $whereis);
        return $result==null?false:true;
    }

    /**
     * @param $info
     * @return bool
     * 注册用户
     */
    public function registerUser($info)
    {
        if($this->select_username($info['username']) == true)    //如果用户名已经存在，则注册失败
        {
            return false;
        }
        $sql = "REPLACE INTO ".$this->_table."(user_uuid, username, email, password, is_del) VALUES('".$info['user_uuid']."', '".$info['username']."', '".$info['email']."', '".$info['password']."', b'0');";
        $result = $this->_db->exec($sql);
        return $result<1?false:true;
    }
}