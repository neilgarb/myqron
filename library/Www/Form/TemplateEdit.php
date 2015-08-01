<?php

class Www_Form_TemplateEdit extends Zend_Dojo_Form
{
	public function __construct($templateId)
	{
		parent::__construct();

		$router = new Shared_Controller_Router();
		$this->setAction(
			$router->assemble(array('id' => $templateId), 'template')
		);

		$this->setMethod('post');

		// privacy

		$privacy = new Zend_Dojo_Form_Element_ComboBox('privacy');
		$privacy->setLabel(__('Privacy'));
		$privacy->setRequired(true);
		$privacy->addMultiOptions(array(
			'private' => __('Private: users can follow this these events'),
			'public' => __('Public: users cannot follow these events')
		));
		$privacy->setValue('public');
		$this->addElement($privacy);

		// web hooks

		$webhooks = new Zend_Dojo_Form_Element_CheckBox('web_hooks');
		$webhooks->setLabel(__('Allow web hooks'));
		$webhooks->setRequired(true);
		$webhooks->setChecked(true);
		$webhooks->setCheckedValue('yes');
		$webhooks->setUncheckedValue('no');
		$this->addElement($webhooks);

		// submit

		$submit = new Zend_Dojo_Form_Element_SubmitButton('submit');
		$submit->setLabel(__('Submit'));
		$this->addElement($submit);
	}
}