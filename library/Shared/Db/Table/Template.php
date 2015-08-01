<?php

class Shared_Db_Table_Template extends Zend_Db_Table_Abstract
{
	protected $_name = 'template';

	protected $_referenceMap = array(
		'Shared_Db_Table_Application' => array(
			'columns' => 'application_id',
			'refTableClass' => 'Shared_Db_Table_Application',
			'refColumns' => 'id'
		)
	);

	protected $_dependentTables = array('Shared_Db_Table_Event');
}