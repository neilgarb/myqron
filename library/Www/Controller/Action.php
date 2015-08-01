<?php

class Www_Controller_Action extends Zend_Controller_Action
{
	public function init()
	{
		parent::init();

		$this->view->doctype('XHTML1_STRICT');
		$this->view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');

		$auth = Zend_Auth::getInstance();
		$this->view->identity = $auth->hasIdentity() ? $auth->getIdentity() : null;

		$this->view->addHelperPath(
			'Www/View/Helper/',
			'Www_View_Helper_'
		);

		// css

		$this->view->headLink()->prependStylesheet('/www/css/myqron.css');

		$controller = $this->getRequest()->getControllerName();
		$action = $this->getRequest()->getActionName();

		$this->view->headLink()->appendStylesheet('/www/css/'.$controller.'.css');
		$this->view->headLink()->appendStylesheet('/www/css/'.$controller.'/'.$action.'.css');
	}
}