<?php

namespace Nebkam\SymfonyTraits\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BadJSONRequestException extends BadRequestHttpException
	{
	public function __construct()
		{
		parent::__construct('Provide POST body as JSON');
		}
	}