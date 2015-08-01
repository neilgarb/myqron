<?php

include_once '../bootstrap.php';

Zend_Layout::startMvc();

$front = Zend_Controller_Front::getInstance();
$front->setDefaultModule('www');
$front->setControllerDirectory(array(
	'www' => BASE.'/modules/www/controllers',
	'api' => BASE.'/modules/api/controllers'
));
$front->setRouter(new Shared_Controller_Router());
$front->dispatch();