<?php

class Myqron
{
	const VERSION = '0.1';

	protected $_email;

	protected $_password;

	protected $_baseUrl = 'http://myqron.ubuntu';

	protected $_resultCode;

	public function __contruct($email, $password)
	{
		$this->_email = (string) $email;
		$this->_password = (string) $password;
	}

	public function template_register($applicationId, $name, $body, $privacy = 'public', $webHooks = true)
	{
		$xml = '<'.'?xml version="1.0" encoding="UTF-8"?'.'>'."\n";
		$xml .= '<template version="'.self::VERSION.'">';
		$xml .= '<applicationId>'.(int) $applicationId.'</applicationId>';
		$xml .= '<name>'.(string) $name.'</name>';
		$xml .= '<body>'.(string) $body.'</body>';
		$xml .= '<privacy>'.(string) $privacy.'</privacy>';
		$xml .= '<webHooks>'.((bool) $webhooks ? 'yes' : 'no').'</webHooks>';
		$xml .= '</template>';

		$result = $this->_post(
			$this->_baseUrl.'/api/template.register',
			$xml
		);

		$templateId = $this->_parseResult($result);

		return $this->getResultCode() == 1 ? $templateId : false;
	}

	public function event_submit($templateId, $tokens)
	{
		$xml = '<'.'?xml version="1.0" encoding="UTF-8"?'.'>'."\n";
		$xml .= '<event version="'.self::VERSION.'">';
		$xml .= '<templateId>'.(int) $templateId.'</templateId>';
		$xml .= '<tokens>';
		foreach ($tokens as $key => $value)
		{
			$xml .= '<token>';
			$xml .= '<name><![CDATA['.(string) $key.']]></name>';
			$xml .= '<value><![CDATA['.(string) $value.']]></value>';
			$xml .= '</token>';
		}
		$xml .= '</tokens>';
		$xml .= '</event>';

		$result = $this->_post(
			$this->_baseUrl.'/api/event.submit',
			$xml
		);

		$this->_parseResult($result);

		return $this->getResultCode() == 1;
	}

	public function getResultCode()
	{
		return (int) $this->_resultCode;
	}

	protected function _post($url, $xml)
	{
		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, $this->_email.':'.$this->_password);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));

		$result = curl_exec($curl);

		curl_close($curl);

		return $result;
	}

	protected function _parseResult($xml)
	{
		$doc = new DOMDocument();
		$doc->loadXml($xml);
		$xpath = new DOMXPath($doc);
		$query = '/result/code';
		$nodes = $xpath->query($query);
		$node = $nodes->item(0);
		$this->_resultCode = (int) $node->nodeValue;

		$query = '/result/return';
		$nodes = $xpath->query($query);
		if (sizeof($nodes) > 0)
		{
			$node = $nodes->item(0);
			return $node->nodeValue;
		}

		return null;
	}
}