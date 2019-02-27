<?php

require_once(SG_REQUEST_PATH.'SGIRequestAdapter.php');
require_once(SG_REQUEST_PATH.'SGResponse.php');

class SGRequestAdapterWordpress implements SGIRequestAdapter
{
	private $headers = array();
	private $params = array();
	private $url;
	private $getWithQueryParams = true;

	private $body;
	private $httpCode;
	private $contentType;

	public function setGetWithQueryParams($getWithQueryParams)
	{
		$this->getWithQueryParams = $getWithQueryParams;
	}

	public function addHeader($header)
	{
		$this->headers[] = $header;
	}

	public function setHeaders($headers)
	{
		$this->headers = $headers;
	}

	public function setUrl($url)
	{
		$this->url = $url;
	}

	public function setParams($params)
	{
		$this->params = $params;
	}

	public function sendPostRequest()
	{
		$body = null;

		if (count($this->params)) {
			// $body = http_build_query($this->params, '', '&');
			$body = $this->params;
		}

		$args = array(
			'headers'     => $this->headers,
			'sslverify'   => false,
			'body'        => $body
		);

		$response = wp_remote_post($this->url, $args);
		$this->body = wp_remote_retrieve_body($response);
		$this->httpCode = wp_remote_retrieve_response_code($response);
		
		$headers = wp_remote_retrieve_headers($response);
		if ($headers && $headers instanceof Requests_Utility_CaseInsensitiveDictionary) {
			$data = $headers->getAll();
			$this->contentType = $data['content-type'];
		}
		else if ($headers && is_array($headers)) {
			$this->contentType = $headers['content-type'];
		}
		else {
			$this->contentType = '';
		}

		return $this->parseResponse();
	}

	public function sendGetRequest()
	{
		$args = array(
			'headers'     => $this->headers,
			'sslverify'   => false
		);

		if (count($this->params)) {
			$this->url = rtrim($this->url, '/').'/';

			if ($this->getWithQueryParams) { //standard get url, with query params
				$this->url .= '?'.http_build_query($this->params, '', '&');
			}
			else { //mvs-styled get url
				$this->url .= implode('/', array_values($this->params));
			}
		}

		$response = wp_remote_get($this->url, $args);
		$this->body = wp_remote_retrieve_body($response);
		$this->httpCode = wp_remote_retrieve_response_code($response);

		$headers = wp_remote_retrieve_headers($response);
		if ($headers && $headers instanceof Requests_Utility_CaseInsensitiveDictionary) {
			$data = $headers->getAll();
			$this->contentType = $data['content-type'];
		}
		else if ($headers && is_array($headers)) {
			$this->contentType = $headers['content-type'];
		}
		else {
			$this->contentType = '';
		}

		return $this->parseResponse();
	}

	public function sendRequest($type)
	{
		$body = null;

		if (count($this->params)) {
			// $body = http_build_query($this->params, '', '&');
			$body = $this->params;
		}

		$args = array(
			'headers'     => $this->headers,
			'sslverify'   => false,
			'method'      => $type,
			'body'        => $body
		);

		$response = wp_remote_request($this->url, $args);
		$this->body = wp_remote_retrieve_body($response);
		$this->httpCode = wp_remote_retrieve_response_code($response);
		
		$headers = wp_remote_retrieve_headers($response);
		if ($headers && $headers instanceof Requests_Utility_CaseInsensitiveDictionary) {
			$data = $headers->getAll();
			$this->contentType = $data['content-type'];
		}
		else if ($headers && is_array($headers)) {
			$this->contentType = $headers['content-type'];
		}
		else {
			$this->contentType = '';
		}

		return $this->parseResponse();
	}

	public function parseResponse()
	{
		$response = new SGResponse();
		$response->setBody($this->body);
		$response->setHttpStatus($this->httpCode);
		$response->setContentType($this->contentType);

		//if the response is in json format, decode it
		if ($this->contentType == 'application/json') {
			$response->parseJsonBody();
		}

		return $response;
	}
}