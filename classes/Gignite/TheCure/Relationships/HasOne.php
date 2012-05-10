<?php
/**
 * A relationship between two models
 *
 * @package     TheCure
 * @category    Attribute
 * @category    Relationships
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure\Relationships;

use Gignite\TheCure\Container;
use Gignite\TheCure\Relation;
use Gignite\TheCure\Models\Model;

class HasOne extends Has implements Relation\Set {

	protected function where($object)
	{
		return $object->{$this->name()};
	}

	/**
	 * Find a single relation.
	 * 
	 * @param   Container
	 * @param   Model
	 * @return  Model
	 */
	public function find(Container $container, Model $model)
	{
		return $this->mapper($container)->find_one(
			$this->where($model->__object()),
			$this->model_suffix());
	}

	/**
	 * Set the one and only relation.
	 * 
	 * @param   Container
	 * @param   Model
	 * @param   Model
	 * @return  void
	 */
	public function set(Container $container, Model $model, Model $relation)
	{
		$relation_object = $relation->__object();

		if ( ! isset($relation_object->_id))
		{
			// If not saved we save the model first
			$this->mapper($container)->save($relation);
		}

		$model->__object()->{$this->name()} = $relation->__object()->_id;
	}

	/**
	 * @param  Container $container
	 * @param  $model
	 * @param  $relation
	 * @return mixed
	 * @throws Relation\FieldNotFoundException
	 */
	public function remove(Container $container, $model, $relation)
	{
		$model_object = $model->__object();

		if (isset($model_object->{$this->name()}))
		{
			unset($model_object->{$this->name()});
			return;
		}

		throw new Relation\FieldNotFoundException;
	}

}