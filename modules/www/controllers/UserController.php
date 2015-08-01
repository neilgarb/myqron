<?php

class UserController extends Www_Controller_Action
{
	public function registerAction()
	{
		$form = new Www_Form_Register();

		if ($this->getRequest()->isPost())
		{
			if ($form->isValid($_POST))
			{
				$values = (object) $form->getValues();

				$userModel = new Shared_Model_User();
				$userId = $userModel->register($values->email, $values->password);
				$userModel->login($values->email, $values->password);

				$this->_redirect(
					$this->view->url(array('id' => $userId), 'user')
				);
			}
		}

		$this->view->form = $form;
	}

	public function loginAction()
	{
		$form = new Www_Form_Login();

		if ($this->getRequest()->isPost())
		{
			if ($form->isValid($_POST))
			{
				$values = (object) $form->getValues();

				$userModel = new Shared_Model_User();
				if ($userId = $userModel->login($values->email, $values->password, $values->remember == 'yes'))
				{
					$session = new Zend_Session_Namespace('Auth');
					if ($session->return !== null)
					{
						$return = $session->return;
						$session->return = null;
						$this->_redirect($return);
					}
					else
					{
						$this->_redirect(
							$this->view->url(array('id' => $userId), 'user')
						);
					}
				}
			}
		}
		elseif (array_key_exists('return', $_GET))
		{
			$session = new Zend_Session_Namespace('Auth');
			$session->return = $_GET['return'];
		}
		else
		{
			$session = new Zend_Session_Namespace('Auth');
			$session->return = null;
		}

		$this->view->form = $form;
	}

	public function logoutAction()
	{
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();

		$this->_redirect('/');
	}

	public function viewAction()
	{
		if ($this->view->identity === null)
		{
			throw new Www_Exception_Auth();
		}

		$id = $this->_getParam('id');

		$userDb = new Shared_Db_Table_User();
		$user = $userDb->fetchRow(array('id = ?' => $id));

		if ($user === null)
		{
			throw new Www_Exception_NotFound();
		}

		if ($user->id != $this->view->identity->id)
		{
			throw new Www_Exception_Access();
		}

		$this->view->user = $user;

		// get this user's applications

		$applicationDb = new Shared_Db_Table_Application();
		$select = $applicationDb
			->select()
			->setIntegrityCheck(false)
			->from('application')
			->joinLeft('template', 'application.id = template.application_id', array('template_count' => 'COUNT(*)'))
			->where('application.user_id = ?', $user->id)
			->group('application.id')
			->order('application.created DESC');
		$this->view->applications = $applicationDb->fetchAll($select);
	}
}