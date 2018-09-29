<?php

Class FavController extends Yaf_Controller_Abstract
{
    public function init()
    {
        $this->_fav = new FavModel();
        $this->_util = new utils();
    }

    function indexAction()
    {
        return true;
    }

    public function addAction()
    {
        $posts['product_uuid'] = $this->getRequest()->getQuery("name");
        $posts['user_uuid'] = Yaf_Session::getInstance()->get("user_uuid");

        if($this->_fav->insert($posts))
        {
            if($this->getRequest()->isXmlHttpRequest())
            {
                exit($this->_util->ret_json(1,"收藏商品成功"));
            }
            $this->redirect("/fav/list");
            return false;
        }
        else
        {
            if($posts['user_uuid']==NULL)
            {
                $this->forward("index", "user", "login");
                return false;
            }
            if($this->getRequest()->isXmlHttpRequest())
            {
                exit($this->_util->reg_json(-1,"收藏商品失败"));
            }
        }
    }
}