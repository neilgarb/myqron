<?php

class Www_Form_Login extends Zend_Dojo_Form
{
	public function __construct()
	{
		parent::__construct();

		$router = new Shared_Controller_Router();
		$this->setAction($router->assemble(array(), 'login'));

		$this->setMethod('post');

		// email

		$email = new Zend_Dojo_Form_Element_TextBox('email');
		$email->setLabel(__('Email'));
		$email->setRequired(true);
		$this->addElement($email);

		// password

		$password = new Zend_Dojo_Form_Element_PasswordTextBox('password');
		$password->setLabel(__('Password'));
		$password->setRequired(true);
		$this->addElement($password);

		// remember

		$remember = new Zend_Dojo_Form_Element_CheckBox('remember');
		$remember->setLabel(__('Remember me on this computer'));
		$remember->setCheckedValue('yes');
		$remember->setUncheckedValue('no');
		$remember->setChecked(true);
		$this->addElement($remember);

		// submit

		$submit = new Zend_Dojo_Form_Element_SubmitButton('submit');
		$submit->setLabel(__('Submit'));
		$this->addElement($submit);
	}
}