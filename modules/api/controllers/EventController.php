<?php

class Api_EventController extends Api_Controller_Action
{
	public function submitAction()
	{
		if (!$this->getRequest()->isPost())
		{
			throw new Api_Exception(Api_Result::ERROR_REQUEST_METHOD);
		}

		$rawPost = file_get_contents('php://input');

		$doc = new DOMDocument();
		$result = @$doc->loadXML($rawPost);

		if ($result === false)
		{
			throw new Api_Exception(Api_Result::ERROR_XML_PARSE);
		}

		$xpath = new DOMXPath($doc);

		// get version

		$query = '/event[@version]';
		$nodes = $xpath->query($query);
		if (sizeof($nodes) !== 1)
		{
			throw new Api_Exception(Api_Result::ERROR_XML_INVALID);
		}
		$version = $nodes->item(0)->getAttribute('version');
		$config = Zend_Registry::get('config');
		if ($version != $config->api->version)
		{
			throw new Api_Exception(Api_Result::ERROR_API_VERSION);
		}

		// get template ID

		$query = '/event/templateId';
		$nodes = $xpath->query($query);
		if (sizeof($nodes) !== 1)
		{
			throw new Api_Exception(Api_Result::ERROR_XML_INVALID);
		}

		$node = $nodes->item(0);
		$templateId = (int) $node->nodeValue;

		$templateDb = new Shared_Db_Table_Template();
		$template = $templateDb->fetchRow(array('id = ?' => $templateId));

		if ($template === null)
		{
			throw new Api_Exception(Api_Result::ERROR_TEMPLATE_NOT_FOUND);
		}

		// get tokens

		$query = '/event/tokens/token';
		$nodes = $xpath->query($query);
		$tokens = array();

		foreach ($nodes as $node)
		{
			$query = 'name';
			$nameNode = $xpath->query($query, $node);
			if (sizeof($nameNode) !== 1)
			{
				throw new Api_Exception(Api_Result::ERROR_XML_INVALID);
			}

			$name = $nameNode->item(0)->nodeValue;

			$query = 'value';
			$valueNode = $xpath->query($query, $node);
			if (sizeof($valueNode) !== 1)
			{
				throw new Api_Exception(Api_Result::ERROR_XML_INVALID);
			}

			$value = $valueNode->item(0)->nodeValue;

			$tokens[$name] = $value;
		}

		// create the event

		try
		{
			$eventModel = new Shared_Model_Event();
			$eventId = $eventModel->register(
				$templateId,
				$tokens
			);
		}
		catch (Exception $e)
		{
			throw new Api_Exception(Api_Result::ERROR_DATABASE);
		}

		$this->getResponse()->setBody(Api_Result::xml(Api_Result::SUCCESS));

		$this->getResponse()->sendResponse();
		die;
	}
}