<?php

namespace Nebkam\SymfonyTraits\EventListener;

use Nebkam\SymfonyTraits\Exception\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ValidationExceptionListener
	{
	public function onKernelException(GetResponseForExceptionEvent $event)
		{
		$exception = $event->getException();

		if ($exception instanceof ValidationException)
			{
			$response = new JsonResponse($exception->getErrors(), $exception->getStatusCode());
			$event->setResponse($response);
			}
		}
	}