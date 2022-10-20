<?php

namespace Nebkam\SymfonyTraits\Test;

use Nebkam\SymfonyTraits\Exception\BadJSONRequestException;
use Nebkam\SymfonyTraits\Test\app\Controller;
use Nebkam\SymfonyTraits\Test\app\FormData;
use Nebkam\SymfonyTraits\Test\app\FormType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\Extension\HttpFoundation\Type\FormTypeHttpFoundationExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;

class FormTraitTest extends KernelTestCase
	{
	public function testEmptyForm(): void
		{
		$request     = new Request();
		$data        = new FormData();
		$formFactory = Forms::createFormFactoryBuilder()
			->addTypeExtension(new FormTypeHttpFoundationExtension())
			->getFormFactory();
		$controller  = new Controller($formFactory);
		$form        = $controller->callHandleForm($request, $data, FormType::class);
		$this->assertInstanceOf(FormInterface::class, $form);
		}

	public function testNoJsonSent(): void
		{
		$request     = new Request();
		$data        = new FormData();
		$formFactory = Forms::createFormFactoryBuilder()
			->addTypeExtension(new FormTypeHttpFoundationExtension())
			->getFormFactory();
		$controller  = new Controller($formFactory);
		$this->expectException(BadJSONRequestException::class);
		$controller->callHandleJsonForm($request, $data, FormType::class);
		}

	public function testGetJsonContent(): void
		{
		$request     = new Request([], [], [], [], [], [], '{"foo":"bar"}');
		$formFactory = Forms::createFormFactoryBuilder()
			->addTypeExtension(new FormTypeHttpFoundationExtension())
			->getFormFactory();
		$controller  = new Controller($formFactory);
		$content     = $controller->callGetJsonContent($request);
		self::assertEquals(['foo' => 'bar'], $content);
		}

	public function testGetJsonValue(): void
		{
		$request     = new Request([], [], [], [], [], [], '{"foo":"bar"}');
		$formFactory = Forms::createFormFactoryBuilder()
			->addTypeExtension(new FormTypeHttpFoundationExtension())
			->getFormFactory();
		$controller  = new Controller($formFactory);
		$value       = $controller->callGetJsonValue($request, 'foo');
		self::assertEquals('bar', $value);
		}
	}
