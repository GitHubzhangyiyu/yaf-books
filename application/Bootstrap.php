<?php

class Bootstrap extends Yaf_Bootstrap_Abstract
{

    private $_config;

    public function _initConfig()
    {
        $this->_config = Yaf_Application::app()->getConfig();
        Yaf_Registry::set('config', $this->_config);
    }
    
    public function _initRoute(Yaf_Dispatcher $dispatcher)
    {
        //在这里注册自己的路由协议,默认使用简单路由
    }
    
    public function _initView(Yaf_Dispatcher $dispatcher)
    {
        //在这里注册自己的view控制器，例如smarty,firekylin
        Yaf_Registry::set('dispatcher', $dispatcher);
    }

    public function _initDb(Yaf_Dispatcher $dispatcher)
    {
        $this->_db = new Db($this->_config->mysql->read->toArray());
        Yaf_Registry::set('_db', $this->_db);
    }
    
    public function _initCache(Yaf_Dispatcher $dispatcher)
    {
        //redis 扩展
        $cache_server = $this->_config->cache;
        if ($cache_server['isopen']!=0)
        {
            $this->_cache = new cache();
            $this->_cache->addServer($cache_server['host'], $cache_server['port']);
            Yaf_Registry::set('_cache', $this->_cache);
        }
    }  

    public function _initSession($dispatcher)
    {
        Yaf_Session::getInstance()->start();
    }

}

    
