<?php

class Api_TemplateController extends Api_Controller_Action
{
	public function registerAction()
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

		$query = '/template[@version]';
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

		// get application ID

		$query = '/template/applicationId';
		$nodes = $xpath->query($query);
		if (sizeof($nodes) !== 1)
		{
			throw new Api_Exception(Api_Result::ERROR_XML_INVALID);
		}

		$node = $nodes->item(0);
		$applicationId = (int) $node->nodeValue;

		$applicationDb = new Shared_Db_Table_Application();
		$application = $applicationDb->fetchRow(array('id = ?' => $applicationId));

		if ($application === null)
		{
			throw new Api_Exception(Api_Result::ERROR_APPLICATION_NOT_FOUND);
		}

		// get template name

		$query = '/template/name';
		$nodes = $xpath->query($query);
		if (sizeof($nodes) !== 1)
		{
			throw new Api_Exception(Api_Result::ERROR_XML_INVALID);
		}

		$node = $nodes->item(0);
		$templateName = $node->nodeValue;

		// get template body

		$query = '/template/body';
		$nodes = $xpath->query($query);
		if (sizeof($nodes) !== 1)
		{
			throw new Api_Exception(Api_Result::ERROR_XML_INVALID);
		}

		$node = $nodes->item(0);
		$templateBody = $node->nodeValue;

		// get template privacy

		$query = '/template/privacy';
		$nodes = $xpath->query($query);
		if (sizeof($nodes) !== 1)
		{
			throw new Api_Exception(Api_Result::ERROR_XML_INVALID);
		}

		$node = $nodes->item(0);
		$templatePrivacy = $node->nodeValue;

		// get web hooks

		$query = '/template/webHooks';
		$nodes = $xpath->query($query);
		if (sizeof($nodes) !== 1)
		{
			throw new Api_Exception(Api_Result::ERROR_XML_INVALID);
		}

		$node = $nodes->item(0);
		$templateWebhooks = $node->nodeValue;

		// create the template

		$templateModel = new Shared_Model_Template();

		try
		{
			$templateId = $templateModel->create(
				$applicationId,
				$templateName,
				$templateBody,
				$templatePrivacy,
				$templateWebhooks
			);
		}
		catch (Exception $e)
		{
			throw new Api_Exception(Api_Result::ERROR_DATABASE);
		}

		$this->getResponse()->setBody(Api_Result::xml(Api_Result::SUCCESS, $templateId));

		$this->getResponse()->sendResponse();
		die;
	}
}