<?php

class Shared_Model_Application
{
	public function create(
		$userId,
		$name,
		$url,
		$description = '',
		$privacy = 'public'
	)
	{
		$applicationDb = new Shared_Db_Table_Application();

		try
		{
			$applicationDb->getAdapter()->beginTransaction();

			$applicationId = $applicationDb->insert(array(
				'user_id' => (int) $userId,
				'name' => (string) $name,
				'description' => (string) $description,
				'url' => (string) $url,
				'privacy' => (string) $privacy,
				'created' => new Zend_Db_Expr('NOW()')
			));

			$applicationDb->getAdapter()->commit();
		}
		catch (Exception $e)
		{
			$applicationDb->getAdapter()->rollBack();

			throw $e;
		}

		return $applicationId;
	}

	public function update($applicationId, $privacy)
	{
		$applicationDb = new Shared_Db_Table_Application();

		try
		{
			$applicationDb->getAdapter()->beginTransaction();

			$applicationId = $applicationDb->update(
				array(
					'privacy' => (string) $privacy
				),
				$applicationDb->getAdapter()->quoteInto('id = ?', $applicationId)
			);

			$applicationDb->getAdapter()->commit();
		}
		catch (Exception $e)
		{
			$applicationDb->getAdapter()->rollBack();

			throw $e;
		}

		return $this;
	}
}