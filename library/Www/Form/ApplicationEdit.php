<?php

class Www_Form_ApplicationEdit extends Zend_Dojo_Form
{
	public function __construct($applicationId)
	{
		parent::__construct();

		$router = new Shared_Controller_Router();
		$this->setAction(
			$router->assemble(array('id' => $applicationId), 'application')
		);

		$this->setMethod('post');

		// privacy

		$privacy = new Zend_Dojo_Form_Element_ComboBox('privacy');
		$privacy->setLabel(__('Privacy'));
		$privacy->setRequired(true);
		$privacy->addMultiOptions(array(
			'private' => __('Private: users can follow this application'),
			'public' => __('Public: users cannot follow this application')
		));
		$privacy->setValue('public');
		$this->addElement($privacy);

		// submit

		$submit = new Zend_Dojo_Form_Element_SubmitButton('submit');
		$submit->setLabel(__('Submit'));
		$this->addElement($submit);
	}
}