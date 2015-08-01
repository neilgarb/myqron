<?php

class ErrorController extends Www_Controller_Action
{
	public function errorAction()
	{
		$handler = $this->_getParam('error_handler');
		$this->view->exception = $handler->exception;

		switch (get_class($this->view->exception))
		{
			case 'Www_Exception_Auth':
				$this->_redirect(
					$this->view->url(array(), 'login').
						'?return='.urlencode($_SERVER['REQUEST_URI'])
				);
				break;

			case 'Api_Exception':
				$this->getResponse()->setHeader('Content-Type', 'text/xml');
				$this->getResponse()->setBody(Api_Result::xml($this->view->exception->getResultCode()));
				$this->getResponse()->sendResponse();
				die;
		}
	}
}