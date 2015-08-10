<?php
/**
 * @brief 数据库格式日志
 * @author JF
 * @date 2015-08-07
 */
class JDBLog implements JLog
{
	//记录的数据表名
	private $tableName = '';

	/**
	 * @brief 构造函数
	 * @param string 要记录的数据表
	 */
	public function __construct($tableName = '')
	{
		$this->tableName = $tableName;
	}

	/**
	 * @brief 向数据库写入log
	 * @param array  log数据
	 * @return bool  操作结果
	 */
	public function write($logs = array())
	{
		if(!is_array($logs) || empty($logs))
		{
			throw new JException('the $logs parms must be array');
		}

		if($this->tableName == '')
		{
			throw new JException('the tableName is undefined');
		}

		$logObj = new JModel($this->tableName);
		$logObj->setData($logs);
		$result = $logObj->add();

		if($result)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * @brief 设置要写入的数据表名称
	 * @param string $tableName 要记录的数据表
	 */
	public function setTableName($tableName)
	{
		$this->tableName = $tableName;
	}
}