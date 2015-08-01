<?php

class Shared_Model_Event
{
	public function register($templateId, $tokens)
	{
		$eventDb = new Shared_Db_Table_Event();
		$tokenDb = new Shared_Db_Table_Token();

		try
		{
			$eventDb->getAdapter()->beginTransaction();

			$eventId = $eventDb->insert(array(
				'template_id' => (int) $templateId,
				'created' => new Zend_Db_Expr('NOW()')
			));

			foreach ($tokens as $key => $value)
			{
				$tokenDb->insert(array(
					'event_id' => (int) $eventId,
					'name' => $key,
					'value' => $value,
					'created' => new Zend_Db_Expr('NOW()')
				));
			}

			$eventDb->getAdapter()->commit();
		}
		catch (Exception $e)
		{
			$eventDb->getAdapter()->rollBack();

			throw $e;
		}

		return $eventId;
	}
}