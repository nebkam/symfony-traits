<?php

namespace Nebkam\SymfonyTraits;

use Nebkam\SymfonyTraits\Exception\ValidationException;
use Nebkam\SymfonyTraits\Exception\BadJSONRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

/**
 * @method Form createForm(string $type, mixed $data, array $options = null)
 */
trait FormTrait
	{
	/**
	 * If this method does not throw an exception, consider the form valid
	 *
	 * @param Request $request
	 * @param mixed $domain
	 * @param string $formType
	 */
	protected function handleForm(Request $request, $domain, $formType)
		{
		$form = $this->createForm($formType,$domain);
		$form->handleRequest($request);

		$this->handleFormErrors($form);
		}

	/**
	 * If this method does not throw an exception, consider the form valid
	 *
	 * @param Request $request Request with JSON posted data
	 * @param object $domain
	 * @param string $formType
	 * @param boolean $clearMissingFields set to TRUE when you want to validate the whole form (i.e. in POST or PUT) or to FALSE when form contains partial data (i.e. in PATCH)
	 * @throws BadJSONRequestException
	 */
	protected function handleJSONForm(Request $request, $domain, $formType, $clearMissingFields = true)
		{
		$data = $this->getJsonContent($request);

		$form = $this->createForm($formType,$domain);
		$form->submit($data,$clearMissingFields);

		$this->handleFormErrors($form);
		}

	/**
	 * @param Request $request
	 * @throws BadJSONRequestException
	 * @return mixed
	 */
	protected function getJsonContent(Request $request)
		{
		$encoded = $request->getContent();
		$content = json_decode($encoded,true);
		if (empty($encoded) || is_null($content))
			{
			throw new BadJSONRequestException;
			}

		return $content;
		}

	/**
	 * @param Form $form
	 * @throws ValidationException
	 */
	protected function handleFormErrors(Form $form)
		{
		if (!$form->isValid())
			{
			$errors = [];
			foreach ($form->getErrors(true) as $error)
				{
				$errors[] = [
					'field' => $error->getOrigin()->getName(),
					'message' => $error->getMessage()
				];
				}
			throw new ValidationException($errors);
			}
		}
	}