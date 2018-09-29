<?php

class ItemController extends Yaf_Controller_Abstract
{
    public function init()
    {
        $this->_item = new ProductModel();
    }

    function getAction()
    {
        $product_name = $this->getRequest()->getQuery("name");
        if($item = $this->_item->select($product_name))
        {
            $this->getView()->assign("item", $item[0]);
        }
        else
        {
            exit("查找失败");
        }
        return true;
    }

    function categoryAction()
    {
        $page = $this->getRequest()->getQuery("page");
        $size = $this->getRequest()->getQuery("size");

        if(!($page&&$size))
        {
            $page=1;
            $size=3;
        }

        $category_id = $this->getRequest()->getQuery("id");
        if($this->_item->select_category($category_id))
        {
            $maxNum = $this->_item->selectAll_num_byCategory($category_id);
            $items = $this->_item->selectPage_byCategory($category_id, $page, $size);
            $this->getView()->assign("items", $items);
            $this->getView()->assign("maxNum",intval($maxNum));
            $this->getView()->assign("curPage",intval($page));
            $this->getView()->assign("curSize",intval($size));
            $this->getView()->assign("category_id",$category_id);
        }
        else
        {
            $this->getView()->assign("error", '查找失败');
        }
        echo $this->render("../index/category");
        return false;
    }

    function searchAction()
    {
        $page = $this->getRequest()->getQuery("page");
        $size = $this->getRequest()->getQuery("size");

        if(!($page&&$size))
        {
            $page=1;
            $size=3;
        }


        $keyword = $this->getRequest()->getQuery("name");
        if( $this->_item->select_name($keyword) )
        {
            $maxNum   = $this->_item->selectAll_num_byName($keyword);

            $items = $this->_item->selectPage_byName($keyword, $page, $size);
            $this->getView()->assign("items", $items);

            $this->getView()->assign("maxNum",intval($maxNum));
            $this->getView()->assign("curPage",intval($page));
            $this->getView()->assign("curSize",intval($size));
            $this->getView()->assign("keyword", $keyword);
        }
        else
        {
            $this->getView()->assign("error",'查找失败');
        }

        echo $this->render("../index/search");

        return false;
    }

}