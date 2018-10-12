<?php

class Bootstrap extends Yaf_Bootstrap_Abstract
{

    private $_config;

    public function _initConfig()
    {
        $this->_config = Yaf_Application::app()->getConfig();
        Yaf_Registry::set('config', $this->_config);
    }

    /**
     * @param Yaf_Dispatcher $dispatcher
     * 路由配置：
     * /item/get?name=64CDD23D-FF1B-DB3C-685E-2F473A366E17 重写成 /item/bookuuid/64CDD23D-FF1B-DB3C-685E-2F473A366E17
     */
    public function _initRoute(Yaf_Dispatcher $dispatcher)
    {
        $router = $dispatcher->getRouter();
        $router->addConfig(Yaf_Registry::get('config')->routes);
    }
    
    public function _initView(Yaf_Dispatcher $dispatcher)
    {
        //在这里注册自己的view控制器，例如smarty,firekylin
        Yaf_Registry::set('dispatcher', $dispatcher);
    }

    /**
     * @param Yaf_Dispatcher $dispatcher
     * @throws Exception
     * 载入Db类， 其中用的是MySQL
     */
    public function _initDb(Yaf_Dispatcher $dispatcher)
    {
        $this->_db = new Db($this->_config->mysql->read->toArray());
        Yaf_Registry::set('_db', $this->_db);
    }

    /**
     * @param Yaf_Dispatcher $dispatcher
     * 载入缓存类redis
     */
    public function _initCache(Yaf_Dispatcher $dispatcher)
    {
        $cache_config['port'] = $this->_config->cache->port;
        $cache_config['host'] = $this->_config->cache->host;
        Yaf_Registry::set('redis', new Rdb($cache_config));
    }

    /**
     * @param $dispatcher
     * 开启session
     */
    public function _initSession($dispatcher)
    {
        Yaf_Session::getInstance()->start();
    }

}

    
