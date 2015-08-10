<?php
/**
 * @brief 控制器内部action
 * @author JF
 * @date 2015-08-07
 */

class JInlineAction extends JAction
{
	/**
	 * @brief 内部action动作执行方法
	 */
	public function run()
	{
		$controller=$this->getController();
		$methodName=$this->getId();
		$controller->$methodName();
	}
}
