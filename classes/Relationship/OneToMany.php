<?php

class Relationship_OneToMany
	extends Relationship implements Relationship_AddRemove {

	public function find(MapperContainer $container, $ids)
	{
		return $this->mapper($container)->find($this->model_suffix(), array(
			'_id' => array('$in' => $ids),
		));
	}

	public function add(MapperContainer $container, $model, $relation)
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

	public function remove(MapperContainer $container, $model, $relation)
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
			
			throw new RelationNotFoundException;
		}

		throw new RelationFieldNotFoundException;
	}

}