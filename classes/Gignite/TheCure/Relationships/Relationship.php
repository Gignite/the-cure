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

abstract class Relationship extends Field {

	protected $name;

	protected $mapper_suffix;

	protected $model_suffix;

	/**
	 *     $this->mapper_class = $mapper_class;
	 *     $this->model_suffix  = $model_suffix;
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

	public function name()
	{
		return $this->name;
	}

	protected function model_suffix()
	{
		return $this->model_suffix;
	}

	protected function mapper_suffix()
	{
		return $this->mapper_suffix;
	}

	protected function mapper(Container $container)
	{
		return $container->mapper($this->mapper_suffix());
	}

	abstract public function find(Container $container, $value);

}