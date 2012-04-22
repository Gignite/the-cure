<?php

class Relationship_OneToMany extends Relationship {

	protected function mapper(MapperContainer $container)
	{
		return $container->mapper($this->mapper());
	}

	public function find(MapperContainer $container, $ids)
	{
		$this->mapper($container)->find($this->model(), array(
			'_id' => array('$in' => $ids),
		));
	}

	public function add(MapperContainer $container, $object, $relation)
	{
		$relation_object = $relation->__object();

		if ( ! isset($relation_object->_id))
		{
			// If not saved we save the model first
			$this->mapper($container)->save($relation);
		}

		$object->__object()->{$this->name()}[] = $model->__object()->_id;
	}

	public function remove(MapperContainer $container, $object, $relation)
	{
		$ids = $object->__object()->{$this->name()};
		
		foreach ($ids as $_k => $_id)
		{
			if ($ids == $model->__object()->_id)
			{
				unset($object->__object()->{$this->name()}[$_k]);
				return;
			}
		}
	}

}