<?php
/**
 * A relationship between models
 *
 * @package     TheCure
 * @category    Attribute
 * @category    Relationships
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure\Relationships;

use Gignite\TheCure\Attribute;
use Gignite\TheCure\Mapper\Container;

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

	/**
	 * @abstract
	 * @param  Container $container
	 * @param  $value
	 * @return mixed
	 */
	abstract public function find(Container $container, $value);

}