<?php return array (
  'logs' => 
  array (
    'path' => 'logs/log',
    'type' => 'file',
  ),
  'DB' =>
  array (
    'type' => 'mysql',
    'tablePre' => 'JF_',
    'read' => 
    array (
      0 => 
      array (
        'host' => '127.0.0.1:3306',
        'user' => 'root',
        'passwd' => '',
        'name' => 'JF',
      ),
    ),
    'write' => 
    array (
      'host' => '127.0.0.1:3306',
      'user' => 'root',
      'passwd' => '',
      'name' => 'JF',
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