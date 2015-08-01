<?php

class Shared_Db_Table_User extends Zend_Db_Table_Abstract
{
	protected $_name = 'user';

	protected $_dependentTables = array('Shared_Db_Table_Application');
}