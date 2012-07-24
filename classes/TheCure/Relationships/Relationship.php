<?php
/**
 * A relationship between models
 * 
 * This abstract class adds two properties to Attribute,
 * $mapperSuffix and $modelSuffix. These are used in child
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

use TheCure\Attributes\Attribute;
use TheCure\Container;

abstract class Relationship extends Attribute {

	protected $mapper;

	protected $model;

	/**
	 * @param  Container $container
	 * @return Mapper
	 */
	public function mapper(Container $container = NULL)
	{
		if ($container !== NULL)
		{
			return $container->mapper($this->mapper());
		}

		return $this->mapper;
	}

	/**
	 * @return mixed
	 */
	public function model()
	{
		return $this->model;
	}

}