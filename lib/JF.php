<?php
/**
 * @brief 引用内核入口文件
 * @author JF
 * @date 2015-8-07
 */
//内核路径
defined('JF_PATH') or define('JF_PATH',dirname(__file__).DIRECTORY_SEPARATOR);
/**
 * @brief JF内核的基类
 * @class JF
 */
class JF
{
	/**
	 * @brief 当前应用的对象
	 */
	public static $app;

	/**
	 * @brief 控制器所在位置
	 */
	public static $_classes = array('controllers.*');

    /**
     * @brief 创建WebApplication应用
     * @param array $config
     * @return object Application对象
     */
    public static function createWebApp($config = null)
    {
        return self::createApp('JFApplication',$config);
    }
    /**
     * @brief 创建Application应用
     * @param string $className
     * @param array $config
     * @return object Application对象
     */
    public static function createApp($className, $config)
    {
        return new $className($config);
    }
	/**
	 * @brief 实现系统类的自动加载
	 * @param String $className 类名称
	 * @return bool true
	 */
	public static function autoload($className)
	{
		if(!preg_match('|^\w+$|',$className))
		{
			die('the class name is inaccurate');
		}

		if(isset(self::$_coreClasses[$className]))
		{
			include(JF_PATH.self::$_coreClasses[$className]);
			return true;
		}
		else if(isset(self::$_classes))
		{
            if(isset(self::$_classes[$className]))
            {
                include(self::parseAlias(self::$_classes[$className]).strtolower( $className ) .'.php');
                return true;
            }
            else
            {
                foreach(self::$_classes as $classPath)
                {
                    $filePath = self::parseAlias($classPath).strtolower( $className ) .'.php';
                    if(is_file($filePath))
                    {
                        include($filePath);
                        return true;
                    }
                }
            }
		}
		return false;
	}

	/**
	 * 把别名路径转化成真实路径
	 * 路径别名，在config配置逻辑中运用，例如:classes.api.goods
	 * 真实路径，在程序运行时运用，例如:classes/api/goods
	 * @param string $alias 别名路径
	 * @return string 真实路径
	 */
	public static function parseAlias($alias)
	{
		return self::$app->getBasePath().strtr(strtolower(trim($alias,'*')),'.','/');
	}

    /**
     * @brief 用户自定义类的注册入口
     * @param array $classes 如:array('system.net.load.*','system.net.ftp.*');
     */
    public static function setClasses($classes)
    {
    	if(is_string($classes))
    	{
    		self::$_classes[] = $classes;
    	}
    	else if(is_array($classes))
    	{
    		self::$_classes += $classes;
    	}
    }
    /**
     * 设置当前框架正在运行的应用
     * @param Object $app 应用对象
     */
    public static function setApplication($exeApp)
    {
    	self::$app = $exeApp;
    }

    //系统内核所有类文件注册信息
	public static $_coreClasses = array(
		'JApplication'      =>  'core/application_class.php',
		'JFApplication'     =>  'core/webapplication_class.php',
        	'JObject'			=>	'core/object_class.php',
        	'JTag'              =>  'core/tag_class.php',
        	'JQuery'            =>  'core/query_class.php',
		'JError'			=>	'core/util/error_class.php',
		'JException'		=>	'core/util/exception_class.php',
		'JHttpException'	=>	'core/util/exception_class.php',
        	'JPaging'			=>	'core/paging_class.php',
		'JHash'				=>	'core/util/hash_class.php',
		'JTime'				=>	'core/util/time_class.php',
		'JValidate'			=>	'core/util/validate_class.php',
		'JServer'			=>	'core/util/server_class.php',
		'JReq'			    =>	'core/util/req_class.php',
		'JFile'				=>	'core/util/file_class.php',
		'JUrl'				=>	'core/util/urlmanager_class.php',
		'JClient'			=>	'core/util/client_class.php',
		'JFilter'			=>	'core/util/filter_class.php',
		'JString'			=>	'core/util/string_class.php',
		'JSmtp'				=>	'core/util/smtp_class.php',
		'JXML'				=>	'core/util/xml_class.php',
		'JUpload'			=>	'core/util/upload_class.php',
		'JCookie'			=>	'core/util/cookie_class.php',
		'JSession'			=>	'core/util/session_class.php',
		'JSON'				=>	'core/util/json_class.php',
		'Captcha'	        =>	'core/util/captcha_class.php',
		'JImage'			=>	'core/util/image_class.php',
		'JLanguage'			=>	'core/util/language_class.php',
		'JSafe'             =>	'core/util/safe_class.php',
		'JCrypt'            =>	'core/util/crypt_class.php',
		'JFileLog'			=>	'log/filelog_class.php',
		'JLog'				=>	'log/log_inte.php',
		'JDBLog'			=>	'log/dblog_class.php',
		'JLogFactory'       =>  'log/log_factory_class.php',
		'JMysql'			=>  'db/driver/mysql_class.php',
		'JDBFactory' 		=>  'db/dbfactory_class.php',
        	'JDB'               =>  'db/db_class.php',
        	'JModel'			=>	'web/model/model_class.php',
        	'JController'		=>	'web/controller/controller_class.php',
        	'JControllerBase'	=>	'web/controller/controllerbase_class.php',
		'JAction'			=>	'web/action/action.php',
		'JInlineAction'     =>  'web/action/inline_action.php',
		'JViewAction'		=>	'web/action/view_action.php',
        	'JSPackage'         =>  'web/js/jspackage_class.php',
        	'JCacheInte'        =>  'core/cache/cache_inte.php',
        	'JFileCache'        =>  'core/cache/filecache_class.php',
        	'JMemCache'         =>  'core/cache/memcache_class.php',
	    	'JRedisCache'       =>  'core/cache/rediscache_class.php',
		'JInterceptor'		=>	'core/interceptor_class.php',
	);
}

spl_autoload_register(array('JF','autoload'));
