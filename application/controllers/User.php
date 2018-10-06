<?php

class UserController extends Yaf_Controller_Abstract
{
    public function init()
    {
        $this->_user = new UserModel();
        $this->_util = new utils();
    }

    public function loginAction()
    {
        if($this->getRequest()->isPost())
        {
            $username = $this->getRequest()->getPost('username');
            $pwd = $this->getRequest()->getPost('password');
            $ret = $this->_user->loginUser($username, sha1(trim($pwd)));
            if($ret)
            {
                Yaf_Session::getInstance()->set("username", $username);
                Yaf_Session::getInstance()->set("user_uuid", $ret);
                $had_order_serial = Yaf_Session::getInstance()->get("order_serial");
                if(!$had_order_serial)
                {
                    $order_serial = date('U').'98'.rand(10000,99999);
                    Yaf_Session::getInstance()->set("order_serial", $order_serial);
                }

                exit($this->_util->ret_json(0, "登陆成功"));

            }
            else
            {
                exit($this->_util->ret_json(1, "登陆失败"));
            }
        }
        return true;
    }

    public function registerAction()
    {
        if($this->getRequest()->isPost())
        {
            $posts = $this->getRequest()->getPost();
            $posts['password'] = sha1($posts['password']);
            $posts['repassword'] = sha1($posts['repassword']);
            $posts['user_uuid'] = $this->_util->guid();
            foreach($posts as $v)
            {
                if(empty($v))
                {
                    //不能为空
                    exit($this->_util->ret_json(2, "不能为空"));
                }
            }
            if($posts['password'] != $posts['repassword'])
            {
                //两次密码不一致
                exit($this->_util->ret_json(3, "两次密码不一致"));
            }
            unset($posts['repassword']);
            unset($posts['submit']);
            if($ret = $this->_user->registerUser($posts))
            {
                exit($this->_util->ret_json(4, "注册成功"));
            }
            else
            {
                exit($this->_util->ret_json(5, "注册失败"));
            }
        }
    }

    public function logoutAction()
    {
        Yaf_Session::getInstance()->del('username');
        Yaf_Session::getInstance()->del('user_uuid');
        Yaf_Session::getInstance()->del('order_serial');
        header('location:/index/');
    }
}