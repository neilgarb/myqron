<?php

class Shared_Db_Table_Event extends Zend_Db_Table_Abstract
{
	protected $_name = 'event';

	protected $_referenceMap = array(
		'Shared_Db_Table_Template' => array(
			'columns' => 'template_id',
			'refTableClass' => 'Shared_Db_Table_Template',
			'refColumns' => 'id'
		)
	);

	protected $_dependentTables = array('Shared_Db_Table_Token');
}