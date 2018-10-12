<?php

class FavModel
{
    protected $_table = 'book_fav';

    public function __construct()
    {
        $this->_db = Yaf_Registry::get('_db');
    }

    /**
     * @param $info
     * @return bool
     * 查找某一本书有没有在收藏夹里面
     */
    public function selectIsIn($info)
    {
        $params = array(
            'product_uuid'
        );
        $whereis = array(
            'AND'=>array('product_uuid'=>$info['product_uuid'], 'user_uuid'=>$info['user_uuid'])
        );
        $result = $this->_db->select($this->_table, $params, $whereis);
        return $result==null?false:true;
    }

    /**
     * @param $info
     * @return bool
     * 把书添加到收藏夹
     */
    public function insert($info)
    {
        if($this->selectIsIn($info)==true)    //先看看书有没有已经在收藏夹里面，有的话直接返回false
        {
            return false;
        }
        else
        {
            $result = $this->_db->insert($this->_table, $info);
            return $result<1?false:$result;
        }
    }

    /**
     * @param $where
     * @return bool
     * 列出某用户收藏夹里的所有书籍
     */
    public function selectAll($where)
    {
        //实现左连接表查询
        $join = array(
            '[>]book_product'=>'product_uuid'
        );
        $params = array(
            'fav_id',
            'product_name',
            'product_uuid',
            'fav_time',
            'writer'
        );
        $whereis = array('user_uuid'=>$where);
        $result = $this->_db->select($this->_table, $join, $params, $whereis);
        return $result==null?false:$result;
    }

    /**
     * @param $fav_id
     * @return bool
     * 删掉收藏夹里面的某条记录
     */
    public function del($fav_id)
    {
        $whereis = array('fav_id'=>$fav_id);
        $result = $this->_db->delete($this->_table, $whereis);
        return $result==null?false:true;
    }
}