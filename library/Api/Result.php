<?php

class Api_Result
{
	const SUCCESS = 1;

	const ERROR_NO_AUTH = 100;
	const ERROR_INVALID_CREDENTIALS = 101;
	const ERROR_API_VERSION = 102;

	const ERROR_REQUEST_METHOD = 200;
	const ERROR_XML_PARSE = 201;
	const ERROR_XML_INVALID = 202;

	const ERROR_DATABASE = 300;

	const ERROR_TEMPLATE_NOT_FOUND = 401;
	const ERROR_TEMPLATE_NOT_OWNED = 402;
	const ERROR_APPLICATION_NOT_FOUND = 403;
	const ERROR_APPLICATION_NOT_OWNED = 404;

	public static function xml($resultId, $return = null)
	{
		$xml = '<'.'?xml version="1.0" encoding="UTF-8"?'.'>'."\n";
		$xml .= '<result><code>'.$resultId.'</code>';

		if ($return !== null)
		{
			$xml .= '<return>'.(string) $return.'</return>';
		}

		$xml .= '</result>';

		return $xml;
	}
}