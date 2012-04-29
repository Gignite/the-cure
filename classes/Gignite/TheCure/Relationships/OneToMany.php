<?php
namespace Gignite\TheCure\Relationships;

use Gignite\TheCure\Mapper\Container;
use Gignite\TheCure\Relation;

class OneToMany	extends Relationship implements Relation\Add, Relation\Remove {

	public function find(Container $container, $ids)
	{
		return $this->mapper($container)->find($this->model_suffix(), array(
			'_id' => array('$in' => $ids),
		));
	}

	public function add(Container $container, $model, $relation)
	{
		$relation_object = $relation->__object();

		if ( ! isset($relation_object->_id))
		{
			// If not saved we save the model first
			$this->mapper($container)->save($relation);
		}

		$object = $model->__object();

		if ( ! isset($object->{$this->name()}))
		{
			$object->{$this->name()} = array();
		}

		$object->{$this->name()}[] = $relation->__object()->_id;
	}

	public function remove(Container $container, $model, $relation)
	{
		$model_object = $model->__object();

		if (isset($model_object->{$this->name()}))
		{
			$ids = $model_object->{$this->name()};
		
			foreach ($ids as $_k => $_id)
			{
				if ($_id == $relation->__object()->_id)
				{
					unset($model_object->{$this->name()}[$_k]);
					return;
				}
			}
			
			throw new Relation\NotFoundException;
		}

		throw new Relation\FieldNotFoundException;
	}

}