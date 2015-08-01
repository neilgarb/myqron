<?php

class ApplicationController extends Www_Controller_Action
{
	public function indexAction()
	{
		$applicationDb = new Shared_Db_Table_Application();
		$select = $applicationDb
			->select()
			->from('application')
			->joinLeft('template', 'application.id = template.application_id', array())
			->where('template.privacy = ?', 'public')
			->group('application.id')
			->order('application.name');
		$this->view->applications = $applicationDb->fetchAll($select);
	}

	public function newAction()
	{
		if ($this->view->identity === null)
		{
			throw new Www_Exception_Auth();
		}

		$form = new Www_Form_ApplicationNew();

		if ($this->getRequest()->isPost())
		{
			if ($form->isValid($_POST))
			{
				$values = (object) $form->getValues();

				$applicationModel = new Shared_Model_Application();
				$applicationId = $applicationModel->create(
					$this->view->identity->id,
					$values->name,
					$values->url,
					$values->description,
					$values->privacy
				);

				$this->_redirect(
					$this->view->url(array('id' => $this->view->identity->id), 'user')
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

		$applicationDb = new Shared_Db_Table_Application();
		$application = $applicationDb->fetchRow(array('id = ?' => $id));

		if ($application === null)
		{
			throw new Www_Exception_NotFound();
		}

		if ($application->user_id != $this->view->identity->id)
		{
			throw new Www_Exception_Access();
		}

		$this->view->application = $application;

		// get templates

		$templateDb = new Shared_Db_Table_Template();
		$this->view->templates = $templateDb->fetchAll(array(
			'application_id = ?' => $application->id
		), 'created DESC');

		// get events

		$eventDb = new Shared_Db_Table_Event();
		$select = $eventDb
			->select()
			->from('event')
			->joinLeft('template', 'event.template_id = template.id', array())
			->where('template.application_id = ?', $application->id)
			->order('event.created DESC');
		$this->view->events = $eventDb->fetchAll($select);

		// form

		$form = new Www_Form_ApplicationEdit($application->id);

		if ($this->getRequest()->isPost())
		{
			if ($form->isValid($_POST))
			{
				$values = (object) $form->getValues();

				$applicationModel = new Shared_Model_Application();
				$applicationModel->update(
					$application->id,
					$values->privacy
				);

				$this->_redirect(
					$this->view->url(array('id' => $application->id), 'application')
				);
			}
		}
		else
		{
			$form->populate(array(
				'privacy' => $application->privacy
			));
		}

		$this->view->form = $form;
	}
}