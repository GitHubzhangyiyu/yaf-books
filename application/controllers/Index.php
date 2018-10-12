<?php

class IndexController extends Yaf_Controller_Abstract
{
    /**
     * @return bool
     * index页面，展示书籍简略信息
     */
    public function indexAction()
    {
        $product = new ProductModel();

        $page = $this->getRequest()->getQuery('page');    //page表示第几页，初始为1
        $size = $this->getRequest()->getQuery('size');    //size表示一页里面放几本书的缩略图，默认为3

        if (!($page&&$size))
        {
            $page = 1;
            $size = 3;
        }

        $itemlist = $product->selectPage($page, $size);    //获取每一页的书籍的简略信息
        $maxNum = $product->selectAll_num();    //返回所有书籍的数目，分页的时候要用

        $this->getView()->assign('items',$itemlist);
        $this->getView()->assign('maxNum',intval($maxNum));
        $this->getView()->assign('curPage',intval($page));
        $this->getView()->assign('curSize',intval($size));

        return true;
    }
}