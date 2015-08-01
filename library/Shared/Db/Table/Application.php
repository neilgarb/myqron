<?php

class Shared_Db_Table_Application extends Zend_Db_Table_Abstract
{
	protected $_name = 'application';

	protected $_referenceMap = array(
		'Shared_Db_Table_User' => array(
			'columns' => 'user_id',
			'refTableClass' => 'Shared_Db_Table_User',
			'refColumns' => 'id'
		)
	);

	protected $_dependentTables = array('Shared_Db_Table_Template');
}