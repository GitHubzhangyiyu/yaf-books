<?php

class ProductModel
{
    protected $_table = "book_product";
    protected $_index = "product_uuid";

    public function __construct()
    {
        $this->_db =  Yaf_Registry::get('_db');
    }

    public function selectPage($page, $size)
    {
        $params = array(
            "product_id",
            "product_uuid",
            "product_name",
            "reg_time",
            "score",
            "category_id",
            "writer",
            "detailed_introduction"
        );

        $page = intval($page);
        $size = intval($size);
        $limit_start = ($page-1)*$size;
        $whereis = array(
            "is_del"=>0,
            "LIMIT"=>array($limit_start,$size)
        );
        $result = $this->_db->select($this->_table, $params, $whereis);
        return $result==null?false:$result;
    }

    //返回所有产品的数目
    public function selectAll_num(){
        $whereis = array(
            "is_del"=>0
        );
        $result = $this->_db->count($this->_table, $whereis);

        return $result;
    }

    //返回某书籍信息
    public function select($product_uuid)
    {
        $params = array(
            "product_id",
            "product_uuid",
            "product_name",
            "reg_time",
            "score",
            "category_id",
            "writer",
            "detailed_introduction"
        );
        $whereis = array(
            "AND"=>array($this->_index=>$product_uuid, "is_del"=>0)
        );
        $result = $this->_db->select($this->_table, $params, $whereis);
        return $result==null?false:$result;
    }

    //返回某一类书籍
    public function select_category($category_id)
    {
        $params = array(
            "product_id",
            "product_uuid",
            "product_name",
            "reg_time",
            "score",
            "category_id",
            "writer",
            "detailed_introduction"
        );
        $whereis = array(
            "AND"=>array('category_id'=>$category_id, "is_del"=>0)
        );
        $result = $this->_db->select($this->_table, $params, $whereis);
        return $result==null?false:$result;
    }

    //返回指定类别的数目
    public function selectAll_num_byCategory($category_id)
    {
        $whereis = array(
            'AND'=>array(
                "is_del"=>0,
                "category_id"=>$category_id
            )
        );
        $result = $this->_db->count($this->_table, $whereis);
        return $result;
    }

    public function selectPage_byCategory($category_id, $page, $size)
    {
        $params = array(
            "product_id",
            "product_uuid",
            "product_name",
            "reg_time",
            "score",
            "category_id",
            "writer",
            "detailed_introduction"
        );
        $page = intval($page);
        $size = intval($size);
        $limit_start = ($page - 1)*$size;
        $whereis = array(
            'AND'=>array(
                "is_del"=>0,
                "category_id"=>$category_id
            ),
            "LIMIT"=>array($limit_start, $size)
        );
        $result = $this->_db->select($this->_table, $params, $whereis);
        return $result==null?false:$result;
    }

    //返回关键词匹配到的所有书籍
    public function select_name($keyword)
    {
        $params = array(
            "product_id",
            "product_uuid",
            "product_name",
            "reg_time",
            "score",
            "category_id",
            "writer",
            "detailed_introduction"
        );
        $product_name = '%'.$keyword.'%';
        $whereis = array(
            "is_del"=>0,
            "LIKE"=>array(
                'product_name'=>$product_name
            )
        );
        $result = $this->_db->select($this->_table, $params, $whereis);
        return $result==null?false:$result;
    }

    public function selectAll_num_byName($keyword)
    {
        $whereis = array(
            'AND'=>array(
                "is_del"=>0,
                "product_name[~]"=>$keyword
            )
        );
        $result = $this->_db->count($this->_table, $whereis);
        return $result;
    }

    public function selectPage_byName($keyword, $page, $size)
    {
        $params = array(
            "product_id",
            "product_uuid",
            "product_name",
            "reg_time",
            "score",
            "category_id",
            "writer",
            "detailed_introduction"
        );
        $page = intval($page);
        $size = intval($size);
        $limit_start = ($page - 1)*$size;
        $whereis = array(
            'AND'=>array(
                "is_del"=>0,
                "product_name[~]"=>$keyword
            ),
            "LIMIT"=>array($limit_start, $size)
        );
        $result = $this->_db->select($this->_table, $params, $whereis);
        return $result==null?false:$result;
    }
}