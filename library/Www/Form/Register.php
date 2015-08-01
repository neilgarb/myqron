<?php

class Www_Form_Register extends Zend_Dojo_Form
{
	public function __construct()
	{
		parent::__construct();

		$router = new Shared_Controller_Router();
		$this->setAction($router->assemble(array(), 'register'));

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

		// submit

		$submit = new Zend_Dojo_Form_Element_SubmitButton('submit');
		$submit->setLabel(__('Submit'));
		$this->addElement($submit);
	}
}