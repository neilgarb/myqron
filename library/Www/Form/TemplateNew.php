<?php

class Www_Form_TemplateNew extends Zend_Dojo_Form
{
	public function __construct($applicationId)
	{
		parent::__construct();

		$router = new Shared_Controller_Router();
		$this->setAction(
			$router->assemble(array(), 'template-new').'?applicationid='.$applicationId
		);

		$this->setMethod('post');

		// name

		$name = new Zend_Dojo_Form_Element_TextBox('name');
		$name->setLabel(__('Name'));
		$name->setRequired(true);
		$this->addElement($name);

		// body

		$body = new Zend_Dojo_Form_Element_Textarea('body');
		$body->setLabel(__('Body'));
		$body->setRequired(true);
		$body->setAttrib('rows', '5');
		$body->setAttrib('cols', '60');
		$body->setDescription(
			__('HTML: You may only use the &lt;a&gt; tag.').'<br />'.
			__('Tokens: Tokens should be %-delimeted, e.g. %post_url%.')
		);
		$body->getDecorator('Description')->setEscape(false);
		$this->addElement($body);

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