<?php
/**
 * @brief memcached内存级缓存类
 * @author JF
 * @date 2015-08-06
 */
class JMemCache implements JCacheInte
{
	private $cache       = null;        //缓存对象
	private $defaultHost = '127.0.0.1'; //默认服务器地址
	private $defaultPort = 11211;       //默认端口号

	//构造函数
	public function __construct()
	{
		if(!extension_loaded('memcache'))
		{
			throw new JHttpException('can not find the memcache extension',403);
			exit;
		}

		$this->cache = new Memcache;
		$server = isset(JF::$app->config['cache']['memcache']['server']) ? JF::$app->config['cache']['memcache']['server'] : $this->defaultHost;
		if(is_array($server))
		{
			foreach($server as $key => $val)
			{
				$this->addServer($val);
			}
		}
		else
		{
			$this->addServe($server);
		}
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
		return $this->cache->addServer($host,$port);
	}

	/**
	 * @brief  写入缓存 如果存在则覆盖
	 * @param  string $key     缓存的唯一key值
	 * @param  mixed  $data    要写入的缓存数据
	 * @param  int    $expire  缓存数据失效时间,单位：秒
	 * @return bool   true:成功;false:失败;
	 */
	public function set($key,$data,$expire = 0,$compress = FALSE)
	{
	    if ($compress) $compress = MEMCACHE_COMPRESSED;
		return $this->cache->set($key,$data,$compress,$expire);
	}
	
	/**
	 * @brief  写入缓存 如果该key存在，则返回失败
	 * @param  string $key     缓存的唯一key值
	 * @param  mixed  $data    要写入的缓存数据
	 * @param  int    $expire  缓存数据失效时间,单位：秒
	 * @return bool   true:成功;false:失败;
	 */
	public function add($key,$data,$expire = 0,$compress = FALSE)
	{
	    if ($compress) $compress = MEMCACHE_COMPRESSED;
	    return $this->cache->add($key,$data,$compress,$expire);
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
	public function del($key,$timeout = 0)
	{
		return $this->cache->delete($key,$timeout);
	}

	/**
	 * @brief  删除全部缓存
	 * @return bool   true:成功；false:失败;
	 */
	public function flush()
	{
		return $this->cache->flush();
	}

}
