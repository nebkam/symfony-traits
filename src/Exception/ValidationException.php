<?php

namespace Nebkam\SymfonyTraits\Exception;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ValidationException extends UnprocessableEntityHttpException
	{
	/**
	 * @var array
	 */
	private $errors;

	/**
	 * @param array $errors
	 */
	public function __construct($errors = array())
		{
		$this->errors = $errors;
		
		parent::__construct();
		}
	
	public function getErrors()
		{
		return $this->errors;
		}
	}