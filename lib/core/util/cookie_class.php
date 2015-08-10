<?php
/**
 * @brief 处理 Cookie
 * @author JF
 * @date 2015-08-10
 */
class JCookie
{
	//cookie前缀
	private static $pre        = 'JF_';

	//默认cookie密钥
	private static $defaultKey = 'JF';

	//安全级别
	private static $level      = 'normal';

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
     * @brief 设置cookie的方法
     * @param string $name 字段名
     * @param string $value 对应的值
     * @param string $time 有效时间
     * @param string $path 工作路径
     * @param string $domain 作用域
     */
	public static function set($name,$value='',$time='3600',$path='/',$domain=null)
	{
		self::$pre = self::getPre();
		if($time <= 0) $time = -100;
		else $time = time() + $time;
		setCookie(self::$pre.'safecode',  JCrypt::encode( self::cookieId() , self::getKey()  )       ,$time,$path,$domain);
		if(is_array($value) || is_object($value)) $value=serialize($value);
		$value = JCrypt::encode($value , self::getKey() );
		setCookie(self::$pre.$name,$value,$time,$path,$domain);
	}

    /**
     * @brief 取得cookie字段值的方法
     * @param string $name 字段名
     * @return mixed 对应的值
     */
	public static function get($name)
	{
		self::$pre  = self::getPre();
		$is_checked = self::checkSafe();

		if($is_checked == 1)
		{
			if(isset($_COOKIE[self::$pre.$name]))
			{
				$cookie= JCrypt::decode($_COOKIE[self::$pre.$name],self::getKey());
				$tem = substr($cookie,0,10);
				if(preg_match('/^[Oa]:\d+:.*/',$tem)) return unserialize($cookie);
				else return $cookie;
			}
			return null;
		}
		else if($is_checked == 0)
		{
			self::clear(self::$pre.'safecode');
		}

		return null;
	}

    /**
     * @brief 清除cookie值的方法
     * @param string $name 字段名
     */
	public static function clear($name)
	{
		self::set($name,'',0);
	}

    /**
     * @brief 清除所有的cookie数据
     */
	public static function clearAll()
	{
		self::$pre = self::getPre();
		$preLen = strlen(self::$pre);
		foreach($_COOKIE as $name => $val)
		{
			if(strpos($name,self::$pre) === 0)
			{
				self::clear(substr($name,$preLen));
			}
		}
	}

    /**
     * @brief 安全检测函数
     * @return int 1:表示通过，0：表示未通过
     */
	private static function checkSafe()
	{
		self::$pre = self::getPre();
		if(isset($_COOKIE[self::$pre.'safecode']))
		{
			if( self::cookieId() == JCrypt::decode($_COOKIE[self::$pre.'safecode'] , self::getKey())    )
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
	 * @brief 取得密钥
	 * @return string 返回密钥值
	 */
	private static function getKey()
	{
		$encryptKey = isset(JF::$app->config['encryptKey']) ? JF::$app->config['encryptKey'] : self::$defaultKey;
		$encryptKey .= self::cookieId();
		return $encryptKey;
	}

    /**
     * @brief 取得cookie的安全码
     * @return String cookie的安全码
     */
	private static function cookieId()
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
