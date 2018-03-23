<?php

namespace Nebkam\SymfonyTraits;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ODM\MongoDB\Query\Builder;
use Nebkam\SymfonyTraits\Annotation\SearchParam;

trait SearchParamParseableTrait
	{
	/**
	 * @param Builder $queryBuilder
	 * @param Reader $annotationReader
	 * @return Builder
	 * @throws \ReflectionException
	 */
	public function parseSearchParam(Builder $queryBuilder, Reader $annotationReader)
		{
		$reflectionClass = new \ReflectionClass($this);

		foreach ($this as $property => $value)
			{
			if ($reflectionClass->hasProperty($property)
				&& !is_null($value))
				{
				$reflectionProperty = $reflectionClass->getProperty($property);
				$annotations = $annotationReader->getPropertyAnnotations($reflectionProperty);
				foreach ($annotations as $annotation)
					{
					if ($annotation instanceof SearchParam)
						{
						$field = $annotation->field ? $annotation->field : $property;

						switch ($annotation->type)
							{
							case 'string':
								$queryBuilder->field($field)->equals((string) $value);
								break;

							case 'string_array':
								if (count($value) > 0)
									{
									$string_values = array_map(function($item){
										return (string) $item;
										}, $value);

									$queryBuilder->field($field)->in($string_values);
									}
								break;

							case 'int':
								$queryBuilder->field($field)->equals((int) $value);
								break;


							case 'int_array':
								if (count($value) > 0)
									{
									$int_values = array_map(function($item){
										return (int) $item;
										}, $value);

									$queryBuilder->field($field)->in($int_values);
									}
								break;

							case 'int_range':
								$annotation->direction === 'from'
									? $queryBuilder->field($field)->gte( (int) $value)
									: $queryBuilder->field($field)->lte( (int) $value);
								break;

							case 'float':
								$queryBuilder->field($field)->equals((float) $value);
								break;

							case 'float_array':
								if (count($value) > 0)
									{
									$int_values = array_map(function($item){
										return (float) $item;
										}, $value);

									$queryBuilder->field($field)->in($int_values);
									}
								break;

							case 'float_range':
								$annotation->direction === 'from'
									? $queryBuilder->field($field)->gte( (float) $value)
									: $queryBuilder->field($field)->lte( (float) $value);
								break;

							case 'bool':
								$queryBuilder->field($field)->equals((bool) $value);
								break;

							case 'exists':
								$queryBuilder->field($field)->exists(true);
								break;

							default:
								if ($annotation->callback)
									{
									$queryBuilder = call_user_func($annotation->callback, $field, $value, $queryBuilder);
									}
								break;
							}
						}
					}
				}
			}

		return $queryBuilder;
		}
	}