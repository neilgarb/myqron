<?php

class TemplateController extends Www_Controller_Action
{
	public function newAction()
	{
		if ($this->view->identity === null)
		{
			throw new Www_Exception_Auth();
		}

		if (!array_key_exists('applicationid', $_GET))
		{
			$this->_redirect(
				$this->view->url(array('id' => $this->view->identity->id), 'user')
			);
		}

		$applicationId = (int) $_GET['applicationid'];
		$applicationDb = new Shared_Db_Table_Application();
		$application = $applicationDb->fetchRow(array('id = ?' => $applicationId));

		if ($application === null)
		{
			throw new Www_Exception_NotFound();
		}

		$this->view->application = $application;

		// form

		$form = new Www_Form_TemplateNew($application->id);

		if ($this->getRequest()->isPost())
		{
			if ($form->isValid($_POST))
			{
				$values = (object) $form->getValues();

				$templateModel = new Shared_Model_Template();
				$templateModel->create(
					$application->id,
					$values->name,
					$values->body,
					$values->privacy,
					$values->web_hooks
				);

				$this->_redirect(
					$this->view->url(array('id' => $application->id), 'application')
				);
			}
		}

		$this->view->form = $form;
	}

	public function viewAction()
	{
		if ($this->view->identity === null)
		{
			throw new Www_Exception_Auth();
		}

		$id = $this->_getParam('id');

		$templateDb = new Shared_Db_Table_Template();
		$template = $templateDb->fetchRow(array('id = ?' => $id));

		if ($template === null)
		{
			throw new Www_Exception_NotFound();
		}

		$application = $template->findParentRow('Shared_Db_Table_Application');
		if ($application->user_id != $this->view->identity->id)
		{
			throw new Www_Exception_Access();
		}

		$this->view->application = $application;
		$this->view->template = $template;

		$form = new Www_Form_TemplateEdit($template->id);

		if ($this->getRequest()->isPost())
		{
			if ($form->isValid($_POST))
			{
				$values = (object) $form->getValues();

				$templateModel = new Shared_Model_Template();
				$templateModel->update(
					$template->id,
					$values->privacy,
					$values->web_hooks
				);

				$this->_redirect(
					$this->view->url(array('id' => $template->id), 'template')
				);

			}
		}
		else
		{
			$form->populate(array(
				'privacy' => $template->privacy,
				'web_hooks' => $template->web_hooks
			));
		}

		$this->view->form = $form;
	}
}