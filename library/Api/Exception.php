<?php

class Api_Exception extends Zend_Exception
{
	protected $_resultCode;

	public function __construct($resultCode)
	{
		$this->_resultCode = (int) $resultCode;
	}

	public function getResultCode()
	{
		return $this->_resultCode;
	}
}