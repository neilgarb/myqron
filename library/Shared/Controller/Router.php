<?php

class Shared_Controller_Router extends Zend_Controller_Router_Rewrite
{
	public function __construct()
	{
		parent::__construct();

		$this->addRoute(
			'register',
			new Zend_Controller_Router_Route(
				'register',
				array(
					'controller' => 'user',
					'action' => 'register'
				)
			)
		);

		$this->addRoute(
			'login',
			new Zend_Controller_Router_Route(
				'login',
				array(
					'controller' => 'user',
					'action' => 'login'
				)
			)
		);

		$this->addRoute(
			'logout',
			new Zend_Controller_Router_Route(
				'logout',
				array(
					'controller' => 'user',
					'action' => 'logout'
				)
			)
		);

		$this->addRoute(
			'user',
			new Zend_Controller_Router_Route(
				'users/:id',
				array(
					'controller' => 'user',
					'action' => 'view'
				),
				array(
					'id' => '\d+'
				)
			)
		);

		$this->addRoute(
			'applications',
			new Zend_Controller_Router_Route(
				'applications',
				array(
					'controller' => 'application',
					'action' => 'index'
				)
			)
		);

		$this->addRoute(
			'application-new',
			new Zend_Controller_Router_Route(
				'applications/new',
				array(
					'controller' => 'application',
					'action' => 'new'
				)
			)
		);

		$this->addRoute(
			'application',
			new Zend_Controller_Router_Route(
				'applications/:id',
				array(
					'controller' => 'application',
					'action' => 'view'
				),
				array(
					'id' => '\d+'
				)
			)
		);

		$this->addRoute(
			'template-new',
			new Zend_Controller_Router_Route(
				'templates/new',
				array(
					'controller' => 'template',
					'action' => 'new'
				)
			)
		);

		$this->addRoute(
			'template',
			new Zend_Controller_Router_Route(
				'templates/:id',
				array(
					'controller' => 'template',
					'action' => 'view'
				),
				array(
					'id' => '\d+'
				)
			)
		);

		$this->addRoute(
			'api-template-register',
			new Zend_Controller_Router_Route(
				'api/template.register',
				array(
					'module' => 'api',
					'controller' => 'template',
					'action' => 'register'
				)
			)
		);

		$this->addRoute(
			'api-event-submit',
			new Zend_Controller_Router_Route(
				'api/event.submit',
				array(
					'module' => 'api',
					'controller' => 'event',
					'action' => 'submit'
				)
			)
		);
	}
}