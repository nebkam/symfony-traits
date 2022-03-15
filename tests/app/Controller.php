<?php

namespace Nebkam\SymfonyTraits\Test\app;

use Nebkam\SymfonyTraits\FormTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class Controller extends AbstractController
	{
	private FormFactoryInterface $formFactory;

	use FormTrait;

	public function __construct(FormFactoryInterface $formFactory)
		{
		$this->formFactory = $formFactory;
		}

	public function callHandleForm(Request $request, $domain, $formType, $options = [])
		{
		return $this->handleForm($request, $domain, $formType, $options);
		}

	public function callHandleJsonForm(Request $request, $domain, $formType, $options = [], $clearMissingFields = true)
		{
		return $this->handleJSONForm($request, $domain, $formType, $options, $clearMissingFields);
		}

	protected function createForm(string $type, $data = null, array $options = []): FormInterface
		{
		return $this->formFactory->create($type, $data, $options);
		}
	}
