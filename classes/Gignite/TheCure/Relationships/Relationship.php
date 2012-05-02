<?php
/**
 * A relationship between models
 *
 * @package     TheCure
 * @category    Field
 * @category    Relationships
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure\Relationships;

use Gignite\TheCure\Field;
use Gignite\TheCure\Mapper\Container;

abstract class Relationship {

	protected $name;

	protected $mapper_suffix;

	protected $model_suffix;

	/**
	 * Create a new relationship.
	 *
	 * @param            $name   Relationship name
	 * @param array|null $config additional config
	 */
	public function __construct($name, array $config = NULL)
	{
		$this->name = $name;

		if ($config)
		{
			foreach ($config as $_k => $_v)
			{
				if (property_exists($this, $_k))
				{
					$this->{$_k} = $_v;
				}
			}
		}
	}

	/**
	 * @return string Relationship name
	 */
	public function name()
	{
		return $this->name;
	}

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
	 * @param  \Gignite\TheCure\Mapper\Container $container
	 * @return \Gignite\TheCure\Mapper\Mapper
	 */
	protected function mapper(Container $container)
	{
		return $container->mapper($this->mapper_suffix());
	}

	/**
	 * @abstract
	 * @param  \Gignite\TheCure\Mapper\Container $container
	 * @param  $value
	 * @return mixed
	 */
	abstract public function find(Container $container, $value);

}