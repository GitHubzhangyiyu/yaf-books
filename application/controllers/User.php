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
            $username = $this->getRequest()->getPost('username');

        }
    }
}