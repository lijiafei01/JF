<?php
/**
 * @brief 数据库工厂类
 * @author JF
 * @date 2015-08-7
 */
class JDBFactory
{
	//数据库对象
	public static $instance   = NULL;

	//默认的数据库连接方式
	private static $defaultDB = 'mysql';

	/**
	 * @brief 创建对象
	 * @return object 数据库对象
	 */
	public static function getDB()
	{
		//单例模式
		if(self::$instance != NULL && is_object(self::$instance))
		{
			return self::$instance;
		}

		//获取数据库配置信息
		if(!isset(JF::$app->config['DB']) || JF::$app->config['DB'] == null)
		{
			throw new JHttpException('can not find DB info in config.php',1000);
			exit;
		}
		$dbinfo = JF::$app->config['DB'];

		//数据库类型
		$dbType = isset($dbinfo['type']) ? $dbinfo['type'] : self::$defaultDB;

		switch($dbType)
		{
			default:
			return self::$instance = new JMysql;
			break;
		}
	}

    private function __construct(){}
    private function __clone(){}
}


?>
