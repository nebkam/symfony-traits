<?php

namespace Nebkam\SymfonyTraits\Annotation;

use Doctrine\Common\Annotations\Annotation\Enum;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class SearchParam
	{
	/**
	 * @Enum({"string", "string_array", "int", "int_array", "int_range", "float", "float_array", "float_range", "bool", "exists"})
	 */
	public $type;

	/**
	 * @Enum({"from", "to"})
	 * Used with ranges
	 */
	public $direction;

	/**
	 * @var string Explicitly name the field that the property value applies to. Defaults to property name.
	 */
	public $field;

	/**
	 * A valid callable that's being called with
	 * - field name
	 * - field value
	 * - the query builder
	 * as arguments
	 *
	 * and should return the decorated query builder
	 *
	 * @var callable
	 */
	public $callback;
	}