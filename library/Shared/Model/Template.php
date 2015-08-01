<?php

class Shared_Model_Template
{
	public function create(
		$applicationId,
		$name,
		$body,
		$privacy = 'public',
		$webhooks = 'yes'
	)
	{
		$templateDb = new Shared_Db_Table_Template();

		try
		{
			$templateDb->getAdapter()->beginTransaction();

			$templateId = $templateDb->insert(array(
				'application_id' => (int) $applicationId,
				'name' => (string) $name,
				'body' => (string) $body,
				'privacy' => (string) $privacy,
				'web_hooks' => (string) $webhooks,
				'created' => new Zend_Db_Expr('NOW()')
			));

			$templateDb->getAdapter()->commit();
		}
		catch (Exception $e)
		{
			$templateDb->getAdapter()->rollBack();

			throw $e;
		}

		return $templateId;
	}

	public function update(
		$templateId,
		$privacy = 'public',
		$webhooks = 'yes'
	)
	{
		$templateDb = new Shared_Db_Table_Template();

		try
		{
			$templateDb->getAdapter()->beginTransaction();

			$templateDb->update(
				array(
					'privacy' => (string) $privacy,
					'web_hooks' => (string) $webhooks
				),
				$templateDb->getAdapter()->quoteInto('id = ?', $templateId)
			);

			$templateDb->getAdapter()->commit();
		}
		catch (Exception $e)
		{
			$templateDb->getAdapter()->rollBack();

			throw $e;
		}

		return $this;
	}
}