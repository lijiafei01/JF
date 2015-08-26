<?php
// +----------------------------------------------------------------------
// | JF [ A simple framework just for learning ]
// +----------------------------------------------------------------------
// | Copyright (c) 20014-2025  All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: JF < lijiafei870705@163.com > < url:http://www.s-le.com >
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.1.0','<'))  die('require PHP > 5.1.0 !');

require dirname(__FILE__)."/lib/JF.php";

$config = dirname(__FILE__)."/config/config.php";

JF::createWebApp($config)->run();

?>