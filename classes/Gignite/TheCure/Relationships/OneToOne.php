<?php
/**
 * A relationship between two models
 *
 * @package     TheCure
 * @category    Field
 * @category    Relationships
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure\Relationships;

use Gignite\TheCure\Mapper\Container;
use Gignite\TheCure\Relation;

class OneToOne	extends Relationship implements Relation\Set, Relation\Remove {

	public function find(Container $container, $id)
	{
		return $this->mapper($container)->find_one($this->model_suffix(), $id);
	}

	public function set(Container $container, $model, $relation)
	{
		$relation_object = $relation->__object();

		if ( ! isset($relation_object->_id))
		{
			// If not saved we save the model first
			$this->mapper($container)->save($relation);
		}

		$model->__object()->{$this->name()} = $relation->__object()->_id;
	}

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