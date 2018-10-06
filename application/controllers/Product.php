<?php

class ProductController extends Yaf_Controller_Abstract
{
    public function init()
    {
        $this->_product = new ProductModel();
        $this->_utils = new utils();
    }

    public function addAction()
    {
        $this->getView()->assign("action",strtolower(
            $this->getRequest()->getControllerName().'_'.$this->getRequest()->getActionName()
        ));

        $admin_username = Yaf_Session::getInstance()->get("admin_username");
        if($admin_username == null)
        {
            $this->forward("Admin","login");
            return false;
        }
        if($this->getRequest()->isPost())
        {
            $posts = $this->getRequest()->getPost();
            unset($posts['submit']);
            foreach($posts as $v)
            {
                if(empty($v))
                {
                    exit($this->_utils->ret_json(0,"不能为空"));
                }
            }
            $posts['product_uuid'] = $this->_utils->guid();
            $posts['is_del'] = false;//默认为false
            if($this->_product->insert($posts))
            {
                exit($this->_utils->ret_json(1,"添加图书成功"));
            }
            else
            {
                exit($this->_utils->ret_json(-1,"添加图书失败"));
            }
        }
    }

    public function editAction()
    {
        if($this->getRequest()->isPost())
        {
            $posts = $this->getRequest()->getPost();
            unset($posts['submit']);
            foreach($posts as $v)
            {
                if(empty($v))
                {
                    exit($this->_utils->ret_json(0,"不能为空"));
                }
            }
            if($this->_product->update($posts['product_uuid'], $posts))
            {
                exit($this->_utils->ret_json(2,"修改图书成功"));
            }
            else
            {
                exit($this->_utils->ret_json(-2,"修改图书失败"));
            }
        }
    }

    public function delAction()
    {
        if($this->getRequest()->isPost())
        {
            $product_uuid = $this->getRequest()->getPost('product_uuid');
            if($this->_product->del($product_uuid))
            {
                exit($this->_utils->ret_json(3,"删除成功"));
            }
            else
            {
                exit($this->_utils->ret_json(-3,"删除失败"));
            }
        }
    }

    public function getAction()
    {
        $product_uuid = $this->getRequest()->getQuery('product_uuid');
        if($product_data = $this->_product->select($product_uuid))
        {
            print_r($product_data);
            return false;
        }
        else
        {
            exit($this->_utils->ret_json(-4,"查找失败"));
        }
    }

    public function listAction()
    {
        $this->getView()->assign("action", strtolower(
            $this->getRequest()->getControllerName().'_'.$this->getRequest()->getActionName()
        ));

        $admin_username = Yaf_Session::getInstance()->get("admin_username");
        if($admin_username == null)
        {
            $this->forward("Admin","login");
            return false;
        }
        $productData = $this->_product->selectAll();
        $this->getView()->assign("productData", $productData);

        if($this->getRequest()->isXmlHttpRequest())
        {
            echo json_encode($productData);
            return false;
        }
        return true;
    }

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