<?php

class UserController extends Yaf_Controller_Abstract
{
    public function init()
    {
        $this->_user = new UserModel();
        $this->_util = new utils();
    }

    /**
     * @return bool
     * 普通用户登录的action
     */
    public function loginAction()
    {
        if($this->getRequest()->isPost())
        {
            $username = $this->getRequest()->getPost('username');
            $pwd = $this->getRequest()->getPost('password');
            $ret = $this->_user->loginUser($username, sha1(trim($pwd)));    //密码用sha1加密后存储
            if($ret)    //登录成功，则在session里保存用户名和用户uuid
            {
                Yaf_Session::getInstance()->set('username', $username);
                Yaf_Session::getInstance()->set('user_uuid', $ret);
                $had_order_serial = Yaf_Session::getInstance()->get('order_serial');
                if(!$had_order_serial)
                {
                    $order_serial = date('U').'98'.rand(10000,99999);
                    Yaf_Session::getInstance()->set('order_serial', $order_serial);
                }

                exit($this->_util->ret_json(0, '登陆成功'));

            }
            else    //登录失败
            {
                exit($this->_util->ret_json(1, '登陆失败'));
            }
        }
        return true;
    }

    /**
     * 普通用户注册的action
     */
    public function registerAction()
    {
        if($this->getRequest()->isPost())
        {
            $posts = $this->getRequest()->getPost();
            $posts['password'] = sha1($posts['password']);
            $posts['repassword'] = sha1($posts['repassword']);
            $posts['user_uuid'] = $this->_util->guid();    //获取用户uuid，由guid函数实现
            foreach($posts as $v)
            {
                if(empty($v))
                {
                    //不能为空
                    exit($this->_util->ret_json(2, '不能为空'));
                }
            }
            if($posts['password'] != $posts['repassword'])
            {
                //两次密码不一致
                exit($this->_util->ret_json(3, '两次密码不一致'));
            }
            unset($posts['repassword']);
            unset($posts['submit']);
            if($ret = $this->_user->registerUser($posts))    //注册成功
            {
                exit($this->_util->ret_json(4, '注册成功'));
            }
            else    //注册失败
            {
                exit($this->_util->ret_json(5, '注册失败'));
            }
        }
    }

    /**
     * 登出的action，清除session里的相关数据，重定向到主页
     */
    public function logoutAction()
    {
        Yaf_Session::getInstance()->del('username');
        Yaf_Session::getInstance()->del('user_uuid');
        Yaf_Session::getInstance()->del('order_serial');
        header('location:/index/');
    }
}