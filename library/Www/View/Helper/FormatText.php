<?php

class Www_View_Helper_FormatText extends Zend_View_Helper_Abstract
{
	public function formatText($string)
	{
		$string = $this->view->escape(trim($string));
		$string = preg_replace('/\r?\n(\r?\n)+/', '</p><p>', $string);
		$string = preg_replace('/(\r?\n)+/', '<br />', $string);
		return '<p>'.$string.'</p>';
	}
}