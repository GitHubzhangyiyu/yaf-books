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
}