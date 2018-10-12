<?php

Class FavController extends Yaf_Controller_Abstract
{
    public function init()
    {
        $this->_fav = new FavModel();
        $this->_util = new utils();
    }

    /**
     * @return bool
     * 添加到收藏夹的action
     */
    public function addAction()
    {
        $posts['product_uuid'] = $this->getRequest()->getQuery('name');
        $posts['user_uuid'] = Yaf_Session::getInstance()->get('user_uuid');    //需要用户uuid 和 图书uuid 两个参数

        if($this->_fav->insert($posts))    //收藏商品成功
        {
            if($this->getRequest()->isXmlHttpRequest())
            {
                exit($this->_util->ret_json(1,'收藏商品成功'));
            }
            $this->redirect('/fav/list');
            return false;
        }
        else
        {
            if($posts['user_uuid']==null)    //用户未登录
            {
                $this->forward('User', 'login');
                return false;
            }
            if($this->getRequest()->isXmlHttpRequest())    //其他情况，收藏失败
            {
                exit($this->_util->reg_json(-1,'收藏商品失败'));
            }
            $this->redirect('/');
            return false;
        }
    }

    /**
     * @return bool
     * 点击收藏夹后，显示登录用户的全部收藏图书。未登录就显示空。
     */
    public function listAction()
    {
        $where = Yaf_Session::getInstance()->get('user_uuid');
        $favData = $this->_fav->selectAll($where);
        $this->getView()->assign('items', $favData);

        return true;
    }

    /**
     * 删除收藏夹里的某本图书
     */
    public function delAction()
    {
        $fav_id = $this->getRequest()->getQuery('name');
        if($this->_fav->del($fav_id))
        {
            exit($this->_util->ret_json(2,'删除商品成功'));
        }
        else
        {
            exit($this->_util->ret_json(-2,'删除商品失败'));
        }
    }
}