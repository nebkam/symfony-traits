<?php

namespace Nebkam\SymfonyTraits\Test;

use Nebkam\SymfonyTraits\Exception\BadJSONRequestException;
use Nebkam\SymfonyTraits\Test\app\Controller;
use Nebkam\SymfonyTraits\Test\app\FormData;
use Nebkam\SymfonyTraits\Test\app\FormType;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\Extension\HttpFoundation\Type\FormTypeHttpFoundationExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;

class FormTraitTest extends KernelTestCase
	{
	/**
	 * @throws ReflectionException
	 */
	public function testHandleForm(): void
		{
		$request     = new Request();
		$data        = new FormData();
		$formFactory = Forms::createFormFactoryBuilder()
			->addTypeExtension(new FormTypeHttpFoundationExtension())
			->getFormFactory();
		$controller  = new Controller($formFactory);
		$form        = TestHelper::callPrivateMethod($controller, 'handleForm', [$request, $data, FormType::class]);
		$this->assertInstanceOf(FormInterface::class, $form);
		}

	/**
	 * @throws ReflectionException
	 */
	public function testNoJsonSent(): void
		{
		$request     = new Request();
		$data        = new FormData();
		$formFactory = Forms::createFormFactoryBuilder()
			->addTypeExtension(new FormTypeHttpFoundationExtension())
			->getFormFactory();
		$controller  = new Controller($formFactory);
		$this->expectException(BadJSONRequestException::class);
		TestHelper::callPrivateMethod($controller, 'handleJsonForm', [$request, $data, FormType::class]);
		}

	/**
	 * @throws ReflectionException
	 */
	public function testGetJsonContent(): void
		{
		$request    = new Request([], [], [], [], [], [], '{"foo":"bar"}');
		$controller = new Controller();
		$content    = TestHelper::callPrivateMethod($controller, 'getJsonContent', [$request]);
		self::assertEquals(['foo' => 'bar'], $content);
		}

	/**
	 * @throws ReflectionException
	 */
	public function testGetJsonValue(): void
		{
		$request    = new Request([], [], [], [], [], [], '{"foo":"bar"}');
		$controller = new Controller();
		$value      = TestHelper::callPrivateMethod($controller, 'getJsonValue', [$request, 'foo']);
		self::assertEquals('bar', $value);
		}
	}
