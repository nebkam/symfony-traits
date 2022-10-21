<?php

namespace Nebkam\SymfonyTraits\Test;

use ReflectionException;
use ReflectionMethod;

class TestHelper
	{
	/**
	 * @param object $object
	 * @param string $method
	 * @param array $args
	 * @return mixed
	 * @throws ReflectionException
	 */
	public static function callPrivateMethod(object $object, string $method, array $args = []): mixed
		{
		$reflectionMethod = new ReflectionMethod(get_class($object), $method);
		/** @noinspection PhpExpressionResultUnusedInspection */
		$reflectionMethod->setAccessible(true);

		return $reflectionMethod->invokeArgs($object, $args);
		}
	}
