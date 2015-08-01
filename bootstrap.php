<?php

if (!function_exists('__'))
{
	function __($string)
	{
		return $string;
	}
}

define('BASE', dirname(__FILE__));

set_include_path(BASE.'/library');

require_once 'Zend/Loader.php';
Zend_Loader::registerAutoload();

$config = new Zend_Config_Ini(BASE.'/config.ini', 'application');
Zend_Registry::set('config', $config);

$db = Zend_Db::factory(
	$config->db->adapter,
	$config->db->config->toArray()
);
Zend_Registry::set('db', $db);
Zend_Db_Table::setDefaultAdapter($db);