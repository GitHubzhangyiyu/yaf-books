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
        $router = $dispatcher->getRouter();
        //在这里注册自己的路由协议,默认使用简单路由
        Yaf_Dispatcher::getInstance()->getRouter()->addRoute(
            "supervar",new Yaf_Route_Supervar("r")
        );
        Yaf_Dispatcher::getInstance()->getRouter()->addRoute(
            "simple", new Yaf_Route_simple('m', 'c', 'a')
        );

        $router->addConfig(Yaf_Registry::get("config")->routes);
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

    //载入缓存类redis
    public function _initCache(Yaf_Dispatcher $dispatcher)
    {
        $cache_config['port'] = $this->_config->cache->port;
        $cache_config['host'] = $this->_config->cache->host;
        Yaf_Registry::set('redis', new Rdb($cache_config));
    }  

    public function _initSession($dispatcher)
    {
        Yaf_Session::getInstance()->start();
    }

}

    
