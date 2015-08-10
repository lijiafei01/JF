<?php
/**
 * @brief 系统JS包加载类文件
 * @author JF
 * @date 2015-08-07
 */
class JSPackage
{
	//系统JS注册表
	private static $JSPackages = array(
		'jquery' => array(
			'js' => array(
				'jquery/jquery-1.9.0.min.js',
				'jquery/jquery-migrate-1.2.1.min.js'
			)
		),

		'form' => array('js' => 'form/form.js'),

		'dialog' => array(
			'js' => array(
				'artdialog/artDialog.js',
				'artdialog/plugins/iframeTools.js'
			),
			'css' => 'artdialog/skins/default.css'
		),

		'kindeditor' => array(
			'js' => array(
				'editor/kindeditor-min.js',
				'editor/lang/zh_CN.js'
			)
		),

		'validate' => array(
			'js'=>'autovalidate/validate.js',
			'css'=>'autovalidate/style.css'
		),

		'my97date' => array('js' => 'my97date/wdatepicker.js'),

		'artTemplate' => array(
			'js' => array(
				'artTemplate/artTemplate.js',
				'artTemplate/artTemplate-plugin.js'
			)
		),
		'cookie' => array('js' => 'cookie/jquery.cookie.js'),
	);

	/**
	 * @brief 加载系统的JS方法
	 * @param $name    string
	 * @param $charset string
	 * @return String
	 */
	public static function load($name,$charset='UTF-8')
	{
		if(!isset(self::$JSPackages[$name]))
		{
			return '';
		}

		$dir = self::getFileOrDir(self::$JSPackages[$name]);

		//如果没有创建就开始拷贝文件
		if(!file_exists(JF::$app->getRuntimePath().'systemjs/'.$dir))
		{
			JFile::xcopy(JF_PATH.'web/js/source/'.$dir,JF::$app->getRuntimePath().'systemjs/'.$dir);
		}

		$webjspath    = JF::$app->getWebRunPath().'/systemjs/';
		$resultString = '';

		foreach(self::$JSPackages[$name] as $key => $val)
		{
			if($key == 'js')
			{
				if(is_array($val))
				{
					foreach($val as $file)
					{
						$resultString .= self::getJsHtml($webjspath.$file,$charset);
					}
				}
				else
				{
					$resultString .= self::getJsHtml($webjspath.$val,$charset);
				}
			}
			else if($key == 'css')
			{
				if(is_array($val))
				{
					foreach($val as $file)
					{
						$resultString .= self::getCssHtml($webjspath.$file,$charset);
					}
				}
				else
				{
					$resultString .= self::getCssHtml($webjspath.$val,$charset);
				}
			}
		}

		return $resultString;
	}

	/**
	 * 获取文件或者目录
	 */
	private static function getFileOrDir($pathInfo)
	{
		if(is_array($pathInfo))
		{
			return self::getFileOrDir(current($pathInfo));
		}
		else
		{
			return dirname($pathInfo);
		}
	}

	/**
	 * 获取JS的html
	 */
	private static function getJsHtml($fileName,$charset)
	{
		return '<script type="text/javascript" charset="'.$charset.'" src="'.$fileName.'"></script>';
	}

	/**
	 * 获取CSS的html
	 */
	private static function getCssHtml($fileName,$charset)
	{
		return '<link rel="stylesheet" type="text/css" href="'.$fileName.'" />';
	}
}