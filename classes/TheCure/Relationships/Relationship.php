<?php
/**
 * A relationship between models
 * 
 * This abstract class adds two properties to Attribute,
 * $mapper_suffix and $model_suffix. These are used in child
 * classes such as HasOne and HasMany when relating one model
 * to another.
 * 
 * No external API is produced by this class.
 *
 * @package     TheCure
 * @category    Relationship
 * @category    Attribute
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Relationships;

use TheCure\Attribute\Attribute;
use TheCure\Container;

abstract class Relationship extends Attribute {

	protected $mapper_suffix;

	protected $model_suffix;

	/**
	 * @return mixed
	 */
	protected function model_suffix()
	{
		return $this->model_suffix;
	}

	/**
	 * @return mixed
	 */
	protected function mapper_suffix()
	{
		return $this->mapper_suffix;
	}

	/**
	 * @param  Container $container
	 * @return Mapper
	 */
	protected function mapper(Container $container)
	{
		return $container->mapper($this->mapper_suffix());
	}

}