<?php

namespace Nebkam\SymfonyTraits;

use Nebkam\SymfonyTraits\Exception\ValidationException;
use Nebkam\SymfonyTraits\Exception\BadJSONRequestException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

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
     * @param array $options
	 * @return Form
	 */
	protected function handleForm(Request $request, $domain, $formType, $options = [])
		{
		$form = $this->createForm($formType,$domain, $options);
		$form->handleRequest($request);
		$this->handleFormErrors($form);

		return $form;
		}

	/**
	 * If this method does not throw an exception, consider the form valid
	 *
	 * @param Request $request Request with JSON posted data
	 * @param object $domain
	 * @param string $formType
     * @param array $options
	 * @param boolean $clearMissingFields set to TRUE when you want to validate the whole form (i.e. in POST or PUT) or to FALSE when form contains partial data (i.e. in PATCH)
	 * @return Form
	 * @throws BadJSONRequestException
	 */
	protected function handleJSONForm(Request $request, $domain, $formType, $options = [], $clearMissingFields = true)
		{
		$data = $this->getJsonContent($request);

		$form = $this->createForm($formType,$domain, $options);
		$form->submit($data,$clearMissingFields);
		$this->handleFormErrors($form);

		return $form;
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
		if (empty($encoded)
			|| $content === null)
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
		if ($form->isSubmitted()
			&& !$form->isValid())
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

	/**
	 * @param ConstraintViolationListInterface $violationList
	 */
	protected function handleValidationErrors(ConstraintViolationListInterface $violationList)
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
	protected function handleUpload(Request $request, $fieldName)
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