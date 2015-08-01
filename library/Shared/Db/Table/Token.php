<?php

class Shared_Db_Table_Token extends Zend_Db_Table_Abstract
{
	protected $_name = 'token';

	protected $_referenceMap = array(
		'Shared_Db_Table_Event' => array(
			'columns' => 'event_id',
			'refTableClass' => 'Shared_Db_Table_Event',
			'refColumns' => 'id'
		)
	);
}