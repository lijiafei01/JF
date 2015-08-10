<?php
/**
 * @brief web应用类
 * @author JF
 * @date 2015-08-07
 */
class JFApplication extends JApplication
{
	public $defaultController = 'site';
    public $controller        = null;

    /**
     * @brief 请求执行方法，是application执行的入口方法
     */
    public function execRequest()
    {
        JUrl::beginUrl();
        $this->controller = $this->createController();
        JInterceptor::run("onCreateController");
        $this->controller->run();
		JInterceptor::run("onFinishController");
    }
    /**
     * @brief 创建当前的Controller对象
     * @return object Controller对象
     */
    public function createController()
    {
        $ctrlId = JUrl::getInfo("controller");

        if($ctrlId == '')
        {
        	$ctrlId = $this->defaultController;
        }

        if(class_exists($ctrlId))
        {
        	$ctrlObject = new $ctrlId($this,$ctrlId);
        }
        else
        {
        	$ctrlObject = new JController($this,$ctrlId);
        }
        $this->controller = $ctrlObject;
        return $this->controller;
    }
    /**
     * @brief 取得当前的Controller
     * @return object Controller对象
     */
    public function getController()
    {
        return $this->controller;
    }
}