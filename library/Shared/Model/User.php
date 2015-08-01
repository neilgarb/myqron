<?php

class Shared_Model_User
{
	public function register($email, $password)
	{
		$userDb = new Shared_Db_Table_User();

		try
		{
			$userDb->getAdapter()->beginTransaction();

			$userId = $userDb->insert(array(
				'email' => $email,
				'password' => md5($password),
				'created' => new Zend_Db_Expr('NOW()')
			));

			$userDb->getAdapter()->commit();
		}
		catch (Exception $e)
		{
			$userDb->getAdapter()->rollBack();

			throw $e;
		}

		return $userId;
	}

	public function login($email, $password, $remember = false)
	{
		$auth = Zend_Auth::getInstance();
		$adapter = new Zend_Auth_Adapter_DbTable(
			Zend_Registry::get('db'),
			'user',
			'email',
			'password',
			'MD5(?)'
		);

		$adapter->setIdentity($email);
		$adapter->setCredential($password);

		$result = $auth->authenticate($adapter);

		if ($result->getCode() == Zend_Auth_Result::SUCCESS)
		{
			$identity = $adapter->getResultRowObject(null, 'password');

			if ((bool) $remember === true)
			{
				Zend_Session::rememberMe(365 * 24 * 60 * 60);
			}
			$auth->getStorage()->write($identity);

			return (int) $identity->id;
		}

		return false;
	}
}