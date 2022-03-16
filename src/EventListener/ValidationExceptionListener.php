<?php

namespace Nebkam\SymfonyTraits\EventListener;

use Nebkam\SymfonyTraits\Exception\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ValidationExceptionListener
	{
	public function onKernelException(ExceptionEvent $event): void
		{
		$exception = $event->getThrowable();

		if ($exception instanceof ValidationException)
			{
			$response = new JsonResponse($exception->getErrors(), $exception->getStatusCode());
			$event->setResponse($response);
			}
		}
	}
