<?php
$JF = dirname(__FILE__)."/lib/JF.php";
$config = dirname(__FILE__)."/config/config.php";
require($JF);
JF::createWebApp($config)->run();
?>