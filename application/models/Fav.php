<?php

class FavModel
{
    protected $_table = "book_fav";

    public function __construct()
    {
        $this->_db = Yaf_Registry::get('_db');
    }

    //查找某一本书有没有在收藏夹里面
    public function selectIsIn($info)
    {
        $params = array(
            "product_uuid"
        );
        $whereis = array(
            "AND"=>array("product_uuid"=>$info['product_uuid'], "user_uuid"=>$info["user_uuid"])
        );
        $result = $this->_db->select($this->_table, $params, $whereis);
        return $result==null?false:true;
    }

    //把书添加到收藏夹
    public function insert($info)
    {
        if($this->selectIsIn($info)==true)
        {
            return false;
        }
        else
        {
            $result = $this->_db->insert($this->_table, $info);
            return $result<1?false:$result;
        }
    }

    //列出某用户收藏夹里的所有书籍
    public function selectAll($where)
    {
        //实现左连接表查询
        $join = array(
            "[>]book_product"=>"product_uuid"
        );
        $params = array(
            "fav_id",
            "product_name",
            "product_uuid",
            "fav_time",
            "comment",
            "writer"
        );
        $whereis = array("user_uuid"=>$where);
        $result = $this->_db->select($this->_table, $join, $params, $whereis);
        return $result==null?false:$result;
    }

    public function del($fav_id)
    {
        $whereis = array('fav_id'=>$fav_id);
        $result = $this->_db->delete($this->_table, $whereis);
        return $result==null?false:true;
    }
}