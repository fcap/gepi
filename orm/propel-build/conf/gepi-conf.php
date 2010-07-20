<?php
// This file generated by Propel 1.5.3-dev convert-conf target
// from XML runtime conf file /opt/lampp/htdocs/gepi/orm/runtime-conf.xml
$conf = array (
  'datasources' => 
  array (
    'gepi' => 
    array (
      'adapter' => 'mysql',
      'connection' => 
      array (
        'classname' => 'PropelPDO',
        //'classname' => 'DebugPDO',
        'dsn' => 'mysql:dbname='.$GLOBALS["dbDb"].';host='.$GLOBALS["dbHost"],
        'user' => $GLOBALS["dbUser"],
        'password' => $GLOBALS["dbPass"],
        'options' => 
        array (
          'ATTR_PERSISTENT' => 
          array (
            'value' => false,
          ),
        ),
        'attributes' => 
        array (
          'ATTR_EMULATE_PREPARES' => 
          array (
            'value' => true,
          ),
	  //utiliser le code ci-dessous si vous avez l'erreur :
	  //SQLSTATE[HY000]: General error: 2014 Cannot execute queries while other unbuffered queries are active
	  
          'PROPEL_ATTR_CACHE_PREPARES' =>
          array (
	    'value' => true,
          ),

          'MYSQL_ATTR_USE_BUFFERED_QUERY' =>
          array (
	    'value' => true,
          ),
        ),
        'settings' => 
        array (
          'charset' => 
          array (
            'value' => 'Latin1',
          ),
        ),
      ),
    ),
    'default' => 'gepi',
  ),
//  'debugpdo' =>
//  array (
//    'logging' =>
//    array (
//      'details' =>
//      array (
//        'method' =>
//        array (
//          'enabled' => true,
//        ),
//        'time' =>
//        array (
//          'enabled' => true,
//        ),
//        'mem' =>
//        array (
//          'enabled' => true,
//        ),
//      ),
//    ),
//  ),
//  'log' =>
//  array (
//    'type' => 'file',
//    'name' => 'propel.log',
//    'ident' => 'propel',
//    'level' => '7',
//    'conf' => '',
//  ),
  'generator_version' => '1.5.3-dev',
);
$conf['classmap'] = include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classmap-gepi-conf.php');
return $conf;