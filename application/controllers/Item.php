<?php

class ItemController extends Yaf_Controller_Abstract
{
    public function init()
    {
        $this->_item = new ProductModel();
        $this->_Rdb = Yaf_Registry::get('redis');
    }

    /**
     * @return bool
     * 展示某一本书的详细信息
     */
    function getAction()
    {
        $product_name = $this->getRequest()->getQuery('name')===null?$this->getRequest()->getParam('name'):$this->getRequest()->getQuery('name');    //重写路由时发现获取参数有问题，就这样处理。。
        if($item = $this->_item->select($product_name))    //按图书uuid返回某书籍的详细信息，如果失败，则提示找不到
        {
            $this->getView()->assign('item', $item[0]);
        }
        else
        {
            exit('查找失败');
        }
        return true;
    }

    /**
     * @return bool
     * 处理分类的操作
     */
    function categoryAction()
    {
        $page = $this->getRequest()->getQuery('page');    //page表示第几页，初始为1
        $size = $this->getRequest()->getQuery('size');    //size表示一页里面放几本书的缩略图，默认为3

        if(!($page&&$size))
        {
            $page=1;
            $size=3;
        }

        $category_id = $this->getRequest()->getQuery('id');    //category_id表示图书分类的id
        if($this->_item->select_category($category_id))    //先按category_id去找找看有没有，如果没有就提示查找失败
        {
            $maxNum = $this->_item->selectAll_num_byCategory($category_id);    //接下来去找该分类的所有图书的数目，分页时要用
            $items = $this->_item->selectPage_byCategory($category_id, $page, $size);    //获取每一页的书籍的简略信息
            $this->getView()->assign('items', $items);
            $this->getView()->assign('maxNum',intval($maxNum));
            $this->getView()->assign('curPage',intval($page));
            $this->getView()->assign('curSize',intval($size));
            $this->getView()->assign('category_id',$category_id);
        }
        else
        {
            $this->getView()->assign('error', '查找失败');
        }
        echo $this->render('../index/category');
        return false;
    }

    /**
     * @return bool
     * 处理搜索操作
     */
    function searchAction()
    {
        $page = $this->getRequest()->getQuery('page');    //page表示第几页，初始为1
        $size = $this->getRequest()->getQuery('size');    //size表示一页里面放几本书的缩略图，默认为3

        if(!($page&&$size))
        {
            $page=1;
            $size=3;
        }

        $keyword = $this->getRequest()->getQuery('name');
        //先从redis缓存里找（数据量大的时候，在redis查找应该会快一点）
        if($this->_Rdb->isExists('keyword:'.$keyword.'curPage'.intval($page).'curSize'.intval($size)))
        {
            /**
             * redis 里用string数据结构存储：
             * key  'keyword:'.$keyword.'curPage'.intval($page).'curSize'.intval($size)
             * 用关键词拼接上页数
             * value
             * 该页面所有书籍的简略信息转化为json
             */
            $array = json_decode($this->_Rdb->get('keyword:'.$keyword.'curPage'.intval($page).'curSize'.intval($size)), true);

            $this->getView()->assign('maxNum',intval($array['maxNum']));
            unset($array['maxNum']);

            $this->getView()->assign('items', $array);


            $this->getView()->assign('curPage',intval($page));
            $this->getView()->assign('curSize',intval($size));
            $this->getView()->assign('keyword', $keyword);
        }
        //缓存里没有再到MySQL里找
        else
        {
            if( $this->_item->select_name($keyword) )
            {
                $maxNum   = $this->_item->selectAll_num_byName($keyword);

                $items = $this->_item->selectPage_byName($keyword, $page, $size);
                $this->getView()->assign('items', $items);

                $this->getView()->assign('maxNum',intval($maxNum));
                $this->getView()->assign('curPage',intval($page));
                $this->getView()->assign('curSize',intval($size));
                $this->getView()->assign('keyword', $keyword);

                $items['maxNum'] = $maxNum;
                $this->_Rdb->set('keyword:'.$keyword.'curPage'.intval($page).'curSize'.intval($size), json_encode($items));
            }
            else
            {
                $this->getView()->assign('error','查找失败');
            }
        }

        echo $this->render('../index/search');

        return false;
    }

}