<?php

class ProductModel
{
    protected $_table = 'book_product';
    protected $_index = 'product_uuid';

    public function __construct()
    {
        $this->_db =  Yaf_Registry::get('_db');
    }

    /**
     * @param $page
     * @param $size
     * @return bool
     * 获取每一页的书籍的简略信息
     */
    public function selectPage($page, $size)
    {
        $params = array(
            'product_id',
            'product_uuid',
            'product_name',
            'reg_time',
            'score',
            'category_id',
            'writer'
        );

        $page = intval($page);
        $size = intval($size);
        $limit_start = ($page-1)*$size;
        $whereis = array(
            'is_del'=>0,
            'LIMIT'=>array($limit_start,$size)
        );
        $result = $this->_db->select($this->_table, $params, $whereis);
        return $result==null?false:$result;
    }

    /**
     * @return mixed
     * 返回所有书籍的数目
     */
    public function selectAll_num(){
        $whereis = array(
            'is_del'=>0
        );
        $result = $this->_db->count($this->_table, $whereis);

        return $result;
    }

    /**
     * @param $product_uuid
     * @return bool
     * 返回某书籍详细信息
     */
    public function select($product_uuid)
    {
        $params = array(
            'product_id',
            'product_uuid',
            'product_name',
            'reg_time',
            'score',
            'category_id',
            'writer',
            'detailed_introduction'
        );
        $whereis = array(
            'AND'=>array($this->_index=>$product_uuid, 'is_del'=>0)
        );
        $result = $this->_db->select($this->_table, $params, $whereis);
        return $result==null?false:$result;
    }

    /**
     * @param $category_id
     * @return bool
     * 返回某一类书籍
     */
    public function select_category($category_id)
    {
        $params = array(
            'product_id',
            'product_uuid',
            'product_name',
            'reg_time',
            'score',
            'category_id',
            'writer'
        );
        $whereis = array(
            'AND'=>array('category_id'=>$category_id, 'is_del'=>0)
        );
        $result = $this->_db->select($this->_table, $params, $whereis);
        return $result==null?false:$result;
    }

    /**
     * @param $category_id
     * @return mixed
     * 返回指定类别的数目
     */
    public function selectAll_num_byCategory($category_id)
    {
        $whereis = array(
            'AND'=>array(
                'is_del'=>0,
                'category_id'=>$category_id
            )
        );
        $result = $this->_db->count($this->_table, $whereis);
        return $result;
    }

    /**
     * @param $category_id
     * @param $page
     * @param $size
     * @return bool
     * 获取某个分类，每一页的书籍的简略信息
     */
    public function selectPage_byCategory($category_id, $page, $size)
    {
        $params = array(
            'product_id',
            'product_uuid',
            'product_name',
            'reg_time',
            'score',
            'category_id',
            'writer'
        );
        $page = intval($page);
        $size = intval($size);
        $limit_start = ($page - 1)*$size;
        $whereis = array(
            'AND'=>array(
                'is_del'=>0,
                'category_id'=>$category_id
            ),
            'LIMIT'=>array($limit_start, $size)
        );
        $result = $this->_db->select($this->_table, $params, $whereis);
        return $result==null?false:$result;
    }

    /**
     * @param $keyword
     * @return bool
     * 返回关键词匹配到的所有书籍
     */
    public function select_name($keyword)
    {
        $params = array(
            'product_id',
            'product_uuid',
            'product_name',
            'reg_time',
            'score',
            'category_id',
            'writer'
        );
        $product_name = '%'.$keyword.'%';
        $whereis = array(
            'is_del'=>0,
            'LIKE'=>array(
                'product_name'=>$product_name
            )
        );
        $result = $this->_db->select($this->_table, $params, $whereis);
        return $result==null?false:$result;
    }

    /**
     * @param $keyword
     * @return mixed
     * 返回搜索关键词返回的数目
     */
    public function selectAll_num_byName($keyword)
    {
        $whereis = array(
            'AND'=>array(
                'is_del'=>0,
                'product_name[~]'=>$keyword
            )
        );
        $result = $this->_db->count($this->_table, $whereis);
        return $result;
    }

    /**
     * @param $keyword
     * @param $page
     * @param $size
     * @return bool
     * 获取某个关键词，每一页的书籍的简略信息
     */
    public function selectPage_byName($keyword, $page, $size)
    {
        $params = array(
            'product_id',
            'product_uuid',
            'product_name',
            'reg_time',
            'score',
            'category_id',
            'writer'
        );
        $page = intval($page);
        $size = intval($size);
        $limit_start = ($page - 1)*$size;
        $whereis = array(
            'AND'=>array(
                'is_del'=>0,
                'product_name[~]'=>$keyword
            ),
            'LIMIT'=>array($limit_start, $size)
        );
        $result = $this->_db->select($this->_table, $params, $whereis);
        return $result==null?false:$result;
    }

    /**
     * @param $product_name
     * @return bool
     * 判断是否已经有了该书籍
     */
    public function select_product_name($product_name)
    {
        $params = array(
            'product_uuid'
        );
        $whereis = array(
            'AND'=>array(
                'product_name'=>$product_name,
                'is_del'=>0
            )
        );
        $result = $this->_db->select($this->_table, $params, $whereis);
        return $result==null?false:true;
    }

    /**
     * @param $info
     * @return bool
     * 新增书籍
     */
    public function insert($info)
    {
        //这里主要是判断是否已经有了该书籍
        if($this->select_product_name($info['product_name'])===false && !($this->select($info[$this->_index])))
        {
            $result = $this->_db->insert($this->_table, $info);
            return $result<1?false:true;
        }
        return false;
    }

    /**
     * @param $product_uuid
     * @param $info
     * @return bool
     * 修改书籍
     */
    public function update($product_uuid, $info)
    {
        $whereis = array($this->_index => $product_uuid);
        $result = $this->_db->update($this->_table, $info, $whereis);

        return $result<1?false:true;
    }

    /**
     * @param $product_uuid
     * @return bool
     * 删除书籍
     */
    public function del($product_uuid)
    {
        $params = array('is_del' => 1);
        $whereis = array($this->_index => $product_uuid);
        $result = $this->_db->update($this->_table, $params, $whereis);

        return $result<1?false:true;
    }

    /**
     * @return bool
     * 返回所有书籍信息
     */
    public function selectAll()
    {
        $params = array(
            'product_id',
            'product_uuid',
            'product_name',
            'reg_time',
            'score',
            'category_id',
            'writer'
        );
        $whereis = array(
            'is_del'=>0
        );
        $result = $this->_db->select($this->_table, $params, $whereis);
        return $result==null?false:$result;
    }
}