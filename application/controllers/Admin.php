<?php

class AdminController extends Yaf_Controller_Abstract
{
    public function init()
    {
        $this->_user = new AdminModel();
    }

    public function indexAction()
    {
        $this->getView()->assign("action", strtolower(
            $this->getRequest()->getControllerName().'_'.$this->getRequest()->getActionName()
        )
        );
        if(Yaf_Session::getInstance()->get("admin_username"))
        {
            $this->getView()->assign("isLogin",true);
        }
        else
        {
            $this->getView()->assign("isLogin",false);
        }
    }

    public function ret_api($code=-1)
    {
        $ret=array();
        $ret[-1]['ret_type']='error_response';
        $ret[-1]['msg']='未知错误';

        $ret[0]['ret_type']='success_response';
        $ret[0]['msg']='登陆成功';

        $ret[1]['ret_type']='error_response';
        $ret[1]['msg']='登陆不成功';

        $ret[2]['ret_type']='error_response';
        $ret[2]['msg']='不能为空';

        $ret[3]['ret_type']='error_response';
        $ret[3]['msg']='两次密码不一致';

        $ret[4]['ret_type']='success_response';
        $ret[4]['msg']='添加成功';

        $ret[5]['ret_type']='error_response';
        $ret[5]['msg']='添加失败';

        $ret[6]['ret_type']='success_response';
        $ret[6]['msg']='修改成功';

        $ret[7]['ret_type']='error_response';
        $ret[7]['msg']='删除失败';


        $util = new utils();
        return $util->ret_json($code, $ret[$code]['msg']);
    }

    public function loginAction()
    {
        $this->getView()->assign("action", strtolower(
            $this->getRequest()->getControllerName().'_'.$this->getRequest()->getActionName()
        ));

        if($this->getRequest()->isPost())
        {
            $username = $this->getRequest()->getPost('username');
            $pwd = $this->getRequest()->getPost('password');

            $return = $this->_user->loginUser($username, sha1(trim($pwd)));
            if($return)
            {
                Yaf_Session::getInstance()->set("admin_username", $username);
                $ret = $this->ret_api(0);
                exit($ret);
            }
            else
            {
                $ret = $this->ret_api(1);
                exit($ret);
            }
        }

        return true;
    }

    public function addAction()
    {
        $this->getView()->assign("action", strtolower(
            $this->getRequest()->getControllerName().'_'.$this->getRequest()->getActionName()
        ));

        if($this->getRequest()->isPost())
        {
            $posts = $this->getRequest()->getPost();
            $posts['password'] = sha1($posts['password']);
            $posts['repassword'] = sha1($posts['repassword']);
            foreach($posts as $v)
            {
                if(empty($v))
                {
                    //不能为空
                    $ret = $this->ret_api(2);
                    exit($ret);
                }
            }
            if($posts['password'] != $posts['repassword'])
            {
                //两次密码不一致
                $ret = $this->ret_api(3);
                exit($ret);
            }
            unset($posts['repassword']);
            unset($posts['submit']);
            if($this->_user->insert($posts))
            {
                //添加成功
                $ret = $this->ret_api(4);
                exit($ret);
            }
            else
            {
                //添加失败
                $ret = $this->ret_api(5);
                exit($ret);
            }
        }
    }
}