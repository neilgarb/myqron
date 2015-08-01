<?php

class Www_Form_ApplicationNew extends Zend_Dojo_Form
{
	public function __construct()
	{
		parent::__construct();

		$router = new Shared_Controller_Router();
		$this->setAction($router->assemble(array(), 'application-new'));

		$this->setMethod('post');

		// name

		$name = new Zend_Dojo_Form_Element_TextBox('name');
		$name->setLabel(__('Name'));
		$name->setRequired(true);
		$this->addElement($name);

		// description

		$description = new Zend_Dojo_Form_Element_Textarea('description');
		$description->setLabel(__('Description'));
		$description->setAttrib('rows', '5');
		$description->setAttrib('cols', '60');
		$this->addElement($description);

		// url

		$url = new Zend_Dojo_Form_Element_TextBox('url');
		$url->setLabel(__('URL'));
		$url->setRequired(true);
		$this->addElement($url);

		// privacy

		$privacy = new Zend_Dojo_Form_Element_ComboBox('privacy');
		$privacy->setLabel(__('Privacy'));
		$privacy->setRequired(true);
		$privacy->addMultiOptions(array(
			'private' => __('Private: users cannot follow this application'),
			'public' => __('Public: users can follow this application')
		));
		$privacy->setValue('public');
		$this->addElement($privacy);

		// submit

		$submit = new Zend_Dojo_Form_Element_SubmitButton('submit');
		$submit->setLabel(__('Submit'));
		$this->addElement($submit);
	}
}