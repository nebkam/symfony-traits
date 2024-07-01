<?php

namespace Nebkam\SymfonyTraits;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Use on classes extending Symfony\Bundle\FrameworkBundle\Controller\AbstractController
 *
 * @method json(mixed $data, int $status = 200, array $headers = [], array[] $context = [])
 */
trait ControllerTrait
	{
	protected function createEmptyResponse(): Response
		{
		return new Response('', Response::HTTP_NO_CONTENT);
		}

	protected function createOkResponse(string $message = ''): Response
		{
		return new Response($message, Response::HTTP_OK);
		}

	protected function jsonWithGroup(mixed $data, string $contextGroup, int $status = Response::HTTP_OK): JsonResponse
		{
		return $this->json($data, $status, [], ['groups' => [$contextGroup]]);
		}
	}