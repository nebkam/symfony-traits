<?php

namespace Nebkam\SymfonyTraits;

use Nebkam\SymfonyTraits\Exception\BadJSONRequestException;
use Nebkam\SymfonyTraits\Exception\ValidationException;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @method FormInterface createForm(string $type, $data = null, array $options = [])
 */
trait FormTrait
	{
	/**
	 * If this method does not throw an exception, consider the form valid
	 *
	 * @param Request $request
	 * @param mixed $domain
	 * @param string $formType
	 * @param array $options
	 * @param bool $forceSubmit
	 * @return FormInterface
	 */
	protected function handleForm(Request $request, $domain, string $formType, array $options = [], bool $forceSubmit = false): FormInterface
		{
		$form = $this->createForm($formType, $domain, $options);
		$form->handleRequest($request);
		if ($forceSubmit && !$form->isSubmitted())
			{
			$form->submit($request->request->all());
			}
		$this->handleFormErrors($form);

		return $form;
		}

	/**
	 * If this method does not throw an exception, consider the form valid
	 *
	 * @param Request $request Request with JSON posted data
	 * @param mixed $domain
	 * @param string $formType
	 * @param array $options
	 * @param boolean $clearMissingFields set to TRUE when you want to validate the whole form (i.e. in POST or PUT) or to FALSE when form contains partial data (i.e. in PATCH)
	 * @return FormInterface
	 * @throws BadJSONRequestException
	 */
	protected function handleJSONForm(Request $request, $domain, string $formType, array $options = [], bool $clearMissingFields = true): FormInterface
		{
		$data = $this->getJsonContent($request);

		$form = $this->createForm($formType, $domain, $options);
		$form->submit($data, $clearMissingFields);
		$this->handleFormErrors($form);

		return $form;
		}

	/**
	 * @param Request $request
	 * @return mixed
	 * @throws BadJSONRequestException
	 */
	protected function getJsonContent(Request $request)
		{
		$encoded = $request->getContent();
		$content = json_decode($encoded, true);
		if (empty($encoded)
			|| $content === null)
			{
			throw new BadJSONRequestException;
			}

		return $content;
		}

	protected function getJsonValue(Request $request, string $name)
		{
		$content = $this->getJsonContent($request);

		return $content[$name] ?? null;
		}

	/**
	 * @param Form $form
	 * @throws ValidationException
	 */
	protected function handleFormErrors(FormInterface $form): void
		{
		if ($form->isSubmitted()
			&& !$form->isValid())
			{
			$errors = [];
			foreach ($form->getErrors(true) as $error)
				{
				$errors[] = [
					'field'   => $error->getOrigin() ? $error->getOrigin()->getName() : '',
					'message' => $error->getMessage()
				];
				}
			throw new ValidationException($errors);
			}
		}

	/**
	 * @param ConstraintViolationListInterface $violationList
	 */
	protected function handleValidationErrors(ConstraintViolationListInterface $violationList): void
		{
		if (\count($violationList))
			{
			$errors = [];
			/** @var ConstraintViolationInterface $violation */
			foreach ($violationList as $violation)
				{
				$errors[] = [
					'field'   => $violation->getPropertyPath(),
					'message' => $violation->getMessage()
				];
				}
			throw new ValidationException($errors);
			}
		}

	/**
	 * If this method does not throw an exception, consider the uploaded file valid
	 *
	 * @param Request $request
	 * @param string $fieldName
	 * @return UploadedFile
	 */
	protected function handleUpload(Request $request, string $fieldName): UploadedFile
		{
		if ($request->files->count() > 0)
			{
			/** @var $file UploadedFile */
			$file = $request->files->get($fieldName);
			if ($file->isValid())
				{
				return $file;
				}

			throw new ValidationException([$file->getErrorMessage()]);
			}

		throw new BadRequestHttpException('No data sent');
		}
	}
