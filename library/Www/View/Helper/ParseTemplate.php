<?php

class Www_View_Helper_ParseTemplate extends Zend_View_Helper_Abstract
{
	public function parseTemplate($templateBody, $tokens)
	{
		if ($tokens instanceof Zend_Db_Table_Rowset)
		{
			$dbTokens = array();

			foreach ($tokens as $token)
			{
				$dbTokens[$token->name] = $token->value;
			}

			$tokens = $dbTokens;
		}

		foreach ($tokens as $key => $value)
		{
			$templateBody = str_replace('%'.$key.'%', $value, $templateBody);
		}

		return $templateBody;
	}
}