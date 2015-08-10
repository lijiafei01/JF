<?php
/**
 * @brief redis内存级缓存类
 * @author JF
 * @date 2015-08-06
 */
class JRedisCache implements JCacheInte
{
	private $cache       = null;        //缓存对象
	private $defaultHost = '127.0.0.1'; //默认服务器地址
	private $defaultPort = 6379;       //默认端口号
	
	//构造函数
	public function __construct()
	{
	   if(!extension_loaded('redis'))
		{
			throw new JHttpException('can not find the redis extension',403);
			exit;
		}

		$this->cache = new Redis();
		$server = isset(JF::$app->config['cache']['redis']['host']) ? JF::$app->config['cache']['redis']['host'] : $this->defaultHost;
		$this->addServe($server);
	}
	
	/**
	 * @brief  添加服务器到连接池
	 * @param  string $address 服务器地址
	 * @return bool   true:成功;false:失败;
	 */
	private function addServe($address)
	{
	    $addressArray = explode(':',$address);
	    $host         = $addressArray[0];
	    $port         = isset($addressArray[1]) ? $addressArray[1] : $this->defaultPort;
	    return $this->cache->connect($host,$port);
	}
	
	/**
	 * @brief  写入缓存
	 * @param  string $key     缓存的唯一key值
	 * @param  mixed  $data    要写入的缓存数据
	 * @param  int    $expire  缓存数据失效时间,单位：秒
	 * @return bool   true:成功;false:失败;
	 */
	public function set($key,$data,$expire = 0)
	{
	    return $this->cache->set($key,$data);
	}

	/**
	 * @brief  读取缓存
	 * @param  string $key 缓存的唯一key值,当要返回多个值时可以写成数组
	 * @return mixed  读取出的缓存数据;null:没有取到数据;
	 */
	public function get($key)
	{
	    return $this->cache->get($key);
	}

	/**
	 * @brief  删除缓存
	 * @param  string $key     缓存的唯一key值
	 * @param  int    $timeout 在间隔单位时间内自动删除,单位：秒
	 * @return bool   true:成功; false:失败;
	 */
	public function del($key,$timeout = '')
	{
	    
	}

	/**
	 * @brief  删除全部缓存
	 * @return bool   true:成功；false:失败;
	 */
	public function flush()
	{
	    
	}
    
}