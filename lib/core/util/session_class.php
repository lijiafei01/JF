<?php
/**
 * @brief session机制处理类
 * @author JF
 * @date 2015-08-10
 */

 //开户session
session_start();

class JSession
{
	//session前缀
	private static $pre='JF_';

	//安全级别
	private static $level = 'normal';

	//获取配置的前缀
	private static function getPre()
	{
		if(isset(JF::$app->config['safePre']))
		{
			return JF::$app->config['safePre'];
		}
		else
		{
			return self::$pre;
		}
	}

	//获取当前的安全级别
	private static function getLevel()
	{
		if(isset(JF::$app->config['safeLevel']))
		{
			return JF::$app->config['safeLevel'];
		}
		else
		{
			return self::$level;
		}
	}

	/**
	 * @brief 设置session数据
	 * @param string $name 字段名
	 * @param mixed $value 对应字段值
	 */
	public static function set($name,$value='')
	{
		self::$pre = self::getPre();
		if(self::checkSafe()==-1) $_SESSION[self::$pre.'safecode']=self::sessionId();
		$_SESSION[self::$pre.$name]=$value;
	}
    /**
     * @brief 获取session数据
     * @param string $name 字段名
     * @return mixed 对应字段值
     */
	public static function get($name)
	{
		self::$pre  = self::getPre();
		$is_checked = self::checkSafe();

		if($is_checked == 1)
		{
			return isset($_SESSION[self::$pre.$name])?$_SESSION[self::$pre.$name]:null;
		}
		else if($is_checked == 0)
		{
			self::clear(self::$pre.'safecode');
		}
		return null;
	}
    /**
     * @brief 清空某一个Session
     * @param mixed $name 字段名
     */
	public static function clear($name)
	{
		self::$pre = self::getPre();
		unset($_SESSION[self::$pre.$name]);
	}
    /**
     * @brief 清空所有Session
     */
	public static function clearAll()
	{
		return session_destroy();
	}

    /**
     * @brief Session的安全验证
     * @return int 1:通过验证,0:未通过验证
     */
	private static function checkSafe()
	{
		self::$pre = self::getPre();
		if(isset($_SESSION[self::$pre.'safecode']))
		{
			if($_SESSION[self::$pre.'safecode']==self::sessionId())
			{
				return 1;
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return -1;
		}
	}
    /**
     * @brief 得到session安全码
     * @return String  session安全码
     */
	private static function sessionId()
	{
		$level = self::getLevel();
		if($level == 'none')
		{
			return '';
		}
		else if($level == 'normal')
		{
			return md5(JClient::getIP());
		}
		return md5(JClient::getIP().$_SERVER["HTTP_USER_AGENT"]);
	}
}
?>
