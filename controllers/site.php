<?php
class Site extends JController
{
    public $layout='site';

	function init()
	{
        
	}

	function index()
	{
	    $testModel = new Config('site_config');
	    $xx = $testModel->getInfo();
	    var_dump($xx);
	    
	    $redis = new JRedisCache();
	    $redis->set("say","Hello JF");
	    echo $redis->get("say");
	    
	    $me = new JMemCache();
	    //$me->del('user:lijiafei');
	    $me->set('user:lijiafei',"hello");
	    $username = $me->get('user:lijiafei');
	    echo $username;
	    $this->site_config   =array('name'=>"欢迎使用JF框架");
	    $this->redirect('index');
	}
}
