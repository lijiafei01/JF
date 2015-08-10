<?php return array (
  'logs' => 
  array (
    'path' => 'logs/log',
    'type' => 'file',
  ),
  'DB' =>
  array (
    'type' => 'mysql',
    'tablePre' => 'JFshop_',
    'read' => 
    array (
      0 => 
      array (
        'host' => 'localhost:3306',
        'user' => 'root',
        'passwd' => 'huigush!@#123',
        'name' => 'huigush',
      ),
    ),
    'write' => 
    array (
      'host' => 'localhost:3306',
      'user' => 'root',
      'passwd' => 'huigush!@#123',
      'name' => 'huigush',
    ),
  ),
  'langPath' => 'language',
  'viewPath' => 'views',
  'models' => 'models.*',
  'rewriteRule' => 'url',
  'theme' => 'default',
  'skin' => 'default',
  'timezone' => 'Etc/GMT-8',
  'upload' => 'upload',
  'dbbackup' => 'backup/database',
  'safe' => 'cookie',
  'safeLevel' => 'none',
  'lang' => 'zh_sc',
  'debug' => true,
  'encryptKey' => '',
  'configExt' => 
  array (
    'site_config' => 'config/site_config.php',
  ),
)?>