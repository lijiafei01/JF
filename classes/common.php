<?php
/**
 * @brief 公共方法类
 * @author JF
 * @date 2015-08-10
 */
class Common
{
	/**
	 * @brief 获取语言包,主题,皮肤的方案
	 * @param string $type  方案类型: theme:主题; skin:皮肤; lang:语言包;
	 * @param string $theme 此参数只有$type为skin时才有用，获取任意theme下的skin方案;
	 * @return string 方案的路径
	 */
	public static function getSitePlan($type,$theme = null)
	{
		$except      = array('.','..','.svn','.htaccess');
		$defaultConf = 'config.php';
		$ctrlObj     = JF::$app->getController();
		$planPath    = null;    //资源方案的路径
		$planList    = array(); //方案列表
		$configKey   = array('name','version','author','time','thumb','info');

		//根据不同的类型设置方案路径
		switch($type)
		{
			case "theme":
			{
				$planPath = dirname($ctrlObj->getViewPath());
				$webPath  = $ctrlObj->themeDir();
			}
			break;

			case "skin":
			{
				$planPath = dirname($ctrlObj->getViewPath()).'/'.$theme.'/'.$ctrlObj->skinDir();
				$webPath  = $ctrlObj->themeDir().'/'.$theme.'/'.$ctrlObj->skinDir();
			}
			break;

			case "lang":
			{
				$planPath = dirname($ctrlObj->getLangPath());
			}
			break;
		}

		if($planPath != null)
		{
			$planList = array();
			$dirRes   = opendir($planPath);

			//遍历目录读取配置文件
			while($dir = readdir($dirRes))
			{
				if(!in_array($dir,$except))
				{
					$fileName = $planPath.'/'.$dir.'/'.$defaultConf;
					$tempData = file_exists($fileName) ? include($fileName) : array();
					if($tempData)
					{
						//拼接系统所需数据
						foreach($configKey as $val)
						{
							if(!isset($tempData[$val]))
							{
								$tempData[$val] = '';
							}
						}

						//缩略图拼接路径
						if(isset($tempData['thumb']) && isset($webPath))
						{
							$tempData['thumb'] = $webPath.'/'.$dir.'/'.$tempData['thumb'];
						}
						$planList[$dir] = $tempData;
					}
				}
			}
		}
		return $planList;
	}
}