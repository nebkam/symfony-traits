<?php

namespace Nebkam\SymfonyTraits\Test;

use Nebkam\SymfonyTraits\Test\app\Controller;
use Nebkam\SymfonyTraits\Test\app\FormData;
use Nebkam\SymfonyTraits\Test\app\FormType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\Extension\HttpFoundation\Type\FormTypeHttpFoundationExtension;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;

class FormTraitTest extends KernelTestCase
	{
	public function testFoo(): void
		{
		$request     = Request::createFromGlobals();
		$data        = new FormData();
		$formFactory = Forms::createFormFactoryBuilder()
			->addTypeExtension(new FormTypeHttpFoundationExtension())
			->getFormFactory();
		$controller  = new Controller($formFactory);
		$controller->callHandleForm($request, $data, FormType::class);
		}
	}
