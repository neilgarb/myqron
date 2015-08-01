<?php

class IndexController extends Www_Controller_Action
{
	public function indexAction()
	{
		$this->view->loginForm = new Www_Form_Login();
	}
}