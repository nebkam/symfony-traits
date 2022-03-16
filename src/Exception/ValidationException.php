<?php

namespace Nebkam\SymfonyTraits\Exception;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ValidationException extends UnprocessableEntityHttpException
	{
	/**
	 * @var array
	 */
	private array $errors;

	/**
	 * @param array $errors
	 */
	public function __construct($errors = [])
		{
		$this->errors = $errors;
		
		parent::__construct();
		}
	
	public function getErrors(): array
		{
		return $this->errors;
		}
	}
