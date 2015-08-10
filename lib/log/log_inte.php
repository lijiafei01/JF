<?php
/**
 * @brief 日志接口文件
 * @author JF
 * @date 2015-08-07
 */
interface JLog
{
    /**
     * @brief 实现日志的写操作接口
     * @param array  $logs 日志的内容
     */
    public function write($logs = array());
}
?>