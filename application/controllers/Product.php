<?php

/**
 * Class ProductController
 * 后台对图书的增删改查在这里
 */
class ProductController extends Yaf_Controller_Abstract
{
    public function init()
    {
        $this->_product = new ProductModel();
        $this->_utils = new utils();
    }

    /**
     * @return bool
     * 新增图书
     */
    public function addAction()
    {
        $this->getView()->assign('action',strtolower(
            $this->getRequest()->getControllerName().'_'.$this->getRequest()->getActionName()
        ));    //传一个string过去（由控制器名字和action名字拼接而成），view里面根据这个来使左侧菜单栏里选中条目的高亮

        $admin_username = Yaf_Session::getInstance()->get('admin_username');
        if($admin_username == null)    //新增图书的操作要先登录
        {
            $this->forward('Admin','login');
            return false;
        }
        if($this->getRequest()->isPost())    //这个if里面处理新增图书操作
        {
            $posts = $this->getRequest()->getPost();
            unset($posts['submit']);
            foreach($posts as $v)    //每个提交的字段都不能为空
            {
                if(empty($v))
                {
                    exit($this->_utils->ret_json(0,'不能为空'));
                }
            }
            $posts['product_uuid'] = $this->_utils->guid();    //获取图书uuid，由guid函数实现
            $posts['is_del'] = false;    //默认为false
            if($this->_product->insert($posts))
            {
                exit($this->_utils->ret_json(1,'添加图书成功'));
            }
            else
            {
                exit($this->_utils->ret_json(-1,'添加图书失败'));
            }
        }
    }

    /**
     * 编辑图书
     */
    public function editAction()
    {
        if($this->getRequest()->isPost())
        {
            $posts = $this->getRequest()->getPost();
            unset($posts['submit']);
            foreach($posts as $v)    //每个提交的字段都不能为空
            {
                if(empty($v))
                {
                    exit($this->_utils->ret_json(0,'不能为空'));
                }
            }
            if($this->_product->update($posts['product_uuid'], $posts))    //根据图书uuid字段来修改
            {
                exit($this->_utils->ret_json(2,'修改图书成功'));
            }
            else
            {
                exit($this->_utils->ret_json(-2,'修改图书失败'));
            }
        }
    }

    /**
     * 删除图书，就是把数据库表里is_del字段改为1
     */
    public function delAction()
    {
        if($this->getRequest()->isPost())
        {
            $product_uuid = $this->getRequest()->getPost('product_uuid');
            if($this->_product->del($product_uuid))    //根据图书uuid字段来删除
            {
                exit($this->_utils->ret_json(3,'删除成功'));
            }
            else
            {
                exit($this->_utils->ret_json(-3,'删除失败'));
            }
        }
    }

    /**
     * @return bool
     * 根据图书uuid来查找图书
     */
    public function getAction()
    {
        $product_uuid = $this->getRequest()->getQuery('product_uuid');
        if($product_data = $this->_product->select($product_uuid))
        {
            print_r($product_data);    //直接把那个数组给打印出来
            return false;
        }
        else
        {
            exit($this->_utils->ret_json(-4,'查找失败'));
        }
    }

    /**
     * @return bool
     * 书籍列表，就是把所有图书信息都列出来，以便上传对应的图片
     */
    public function listAction()
    {
        $this->getView()->assign('action', strtolower(
            $this->getRequest()->getControllerName().'_'.$this->getRequest()->getActionName()
        ));    //传一个string过去（由控制器名字和action名字拼接而成），view里面根据这个来使左侧菜单栏里选中条目的高亮

        $admin_username = Yaf_Session::getInstance()->get('admin_username');
        if($admin_username == null)    //进到这个页面也要做管理员登录验证
        {
            $this->forward('Admin','login');
            return false;
        }
        $productData = $this->_product->selectAll();
        $this->getView()->assign('productData', $productData);

        if($this->getRequest()->isXmlHttpRequest())
        {
            echo json_encode($productData);
            return false;
        }
        return true;
    }

    /**
     * @return bool
     * 上传图片的action，以图书uuid命名
     */
    public function uploadAction()
    {
        $uploads_dir = APP_PATH.'public/images/product/';
        $filename = array_keys($_FILES);
        $type = $_FILES[$filename[0]]['type'];
        $subfix = '';
        if ( $type == 'image/jpeg' ||  $type == 'image/png'  )
        {
            $subfix = '.jpg';
        }
        move_uploaded_file($_FILES[$filename[0]]['tmp_name'], $uploads_dir . $filename[0] . $subfix );
        return false;
    }
}