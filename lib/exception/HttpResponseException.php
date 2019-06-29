<?php

namespace proton\lib\exception;

use RuntimeException;
use proton\lib\Response;

class HttpResponseException extends RuntimeException{
	/**
	 * @var Response
	 */
	protected $response;

	public function __construct(Response $response){
		$this->response = $response;
	}

	public function getResponse(){
		return $this->response;
	}
}
