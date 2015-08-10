<?php
/**
 * @brief 日志接口文件
 * @author JF
 * @date 2015-08-07
 */
class JLogFactory
{
    private static $log      = null;         //日志对象
    private static $logClass = array('file' => 'JFileLog' , 'db' => 'JDBLog');

    /**
     * @brief   生成日志处理对象，包换各种介质的日志处理对象,单例模式
     * @logType string $logType 日志类型
     * @return  object 日志对象
     */
    public static function getInstance($logType = '')
    {
    	$className = isset(self::$logClass[$logType]) ? self::$logClass[$logType] : '';
    	if(!class_exists($className))
    	{
    		throw new JException('the log class is not exists',403);
    	}

    	if(!self::$log instanceof $className)
    	{
    		self::$log = new $className;
    	}
    	return self::$log;
    }

    private function __construct(){}
    private function __clone(){}
}
?>
