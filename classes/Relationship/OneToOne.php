<?php

class Relationship_OneToOne
	extends Relationship
		implements Relationship_Set, Relationship_Remove {

	public function find(MapperContainer $container, $id)
	{
		return $this->mapper($container)->find_one($this->model_suffix(), $id);
	}

	public function set(MapperContainer $container, $model, $relation)
	{
		$relation_object = $relation->__object();

		if ( ! isset($relation_object->_id))
		{
			// If not saved we save the model first
			$this->mapper($container)->save($relation);
		}

		$model->__object()->{$this->name()} = $relation->__object()->_id;
	}

	public function remove(MapperContainer $container, $model, $relation)
	{
		$model_object = $model->__object();

		if (isset($model_object->{$this->name()}))
		{
			unset($model_object->{$this->name()});
			return;
		}

		throw new RelationFieldNotFoundException;
	}

}