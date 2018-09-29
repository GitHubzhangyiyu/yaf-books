<?php

class IndexController extends Yaf_Controller_Abstract
{
    public function indexAction()
    {
        $product = new ProductModel();

        $page = $this->getRequest()->getQuery("page");
        $size = $this->getRequest()->getQuery("size");

        if (!($page&&$size))
        {
            $page = 1;
            $size = 3;
        }

        $itemlist = $product->selectPage($page, $size);
        $maxNum = $product->selectAll_num();

        $this->getView()->assign("items",$itemlist);
        $this->getView()->assign("maxNum",intval($maxNum));
        $this->getView()->assign("curPage",intval($page));
        $this->getView()->assign("curSize",intval($size));

        return true;
    }
}