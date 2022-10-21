<?php

namespace Nebkam\SymfonyTraits\Test\app;

use Nebkam\SymfonyTraits\FormTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class Controller extends AbstractController
	{
	private ?FormFactoryInterface $formFactory;

	use FormTrait;

	public function __construct(?FormFactoryInterface $formFactory = null)
		{
		$this->formFactory = $formFactory;
		}

	protected function createForm(string $type, $data = null, array $options = []): FormInterface
		{
		return $this->formFactory->create($type, $data, $options);
		}
	}
