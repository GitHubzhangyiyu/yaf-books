<?php

/**
 * Class AdminController
 * 后台管理员登录和添加管理员在这里
 */
class AdminController extends Yaf_Controller_Abstract
{
    public function init()
    {
        $this->_user = new AdminModel();
    }

    /**
     *从session获取信息，来判断有没有登录
     */
    public function indexAction()
    {
        $this->getView()->assign('action', strtolower(
            $this->getRequest()->getControllerName().'_'.$this->getRequest()->getActionName()
        )
        );    //传一个string过去（由控制器名字和action名字拼接而成），view里面根据这个来使左侧菜单栏里选中条目的高亮
        if(Yaf_Session::getInstance()->get('admin_username'))
        {
            $this->getView()->assign('isLogin',true);
        }
        else
        {
            $this->getView()->assign('isLogin',false);
        }
    }

    /**
     * @param int $code
     * @return false|string
     * 设置好各种返回信息，以json格式提醒用户
     */
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

    /**
     * @return bool
     *登录的action，如果登录成功了就在session里保存用户名
     */
    public function loginAction()
    {
        $this->getView()->assign('action', strtolower(
            $this->getRequest()->getControllerName().'_'.$this->getRequest()->getActionName()
        ));    //传一个string过去（由控制器名字和action名字拼接而成），view里面根据这个来使左侧菜单栏里选中条目的高亮

        if($this->getRequest()->isPost())
        {
            $username = $this->getRequest()->getPost('username');
            $pwd = $this->getRequest()->getPost('password');

            $return = $this->_user->loginUser($username, sha1(trim($pwd)));
            if($return)    //登录成功
            {
                Yaf_Session::getInstance()->set('admin_username', $username);
                $ret = $this->ret_api(0);
                exit($ret);
            }
            else    //登录不成功
            {
                $ret = $this->ret_api(1);
                exit($ret);
            }
        }

        return true;
    }

    /**
     * @return bool
     * 新增管理员；或者修改已经存在的管理员密码，邮箱等信息
     */
    public function addAction()
    {
        $this->getView()->assign('action', strtolower(
            $this->getRequest()->getControllerName().'_'.$this->getRequest()->getActionName()
        ));    //传一个string过去（由控制器名字和action名字拼接而成），view里面根据这个来使左侧菜单栏里选中条目的高亮

        $admin_username = Yaf_Session::getInstance()->get('admin_username');
        if($admin_username === null)    //添加管理员的操作要先登录
        {
            $this->forward('login');
            return false;
        }
        if($this->getRequest()->isPost())    //这个if里面处理添加管理员操作
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

    /**
     * 登出的action，把session里面的用户名删掉
     */
    public function logoutAction()
    {
        Yaf_Session::getInstance()->del('admin_username');
        header('Location:/admin/');
    }
}