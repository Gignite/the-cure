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

	protected $mapperSuffix;

	protected $modelSuffix;

	/**
	 * @return mixed
	 */
	protected function modelSuffix()
	{
		return $this->modelSuffix;
	}

	/**
	 * @return mixed
	 */
	protected function mapperSuffix()
	{
		return $this->mapperSuffix;
	}

	/**
	 * @param  Container $container
	 * @return Mapper
	 */
	protected function mapper(Container $container)
	{
		return $container->mapper($this->mapperSuffix());
	}

}