<?php

abstract class Mapper_Mongo extends Mapper {

	public function find($suffix = NULL, array $where = NULL)
	{
		if ($where === NULL)
		{
			if ($suffix === NULL OR is_string($suffix))
			{
				$where = array();
			}
			elseif (is_array($suffix))
			{
				$where = $suffix;
				$suffix = NULL;
			}
			else
			{
				throw new InvalidArgumentException;
			}
		}

		$cursor = $this->collection()->find($where, $options);
		$class = $this->model_class($suffix);

		return new Collection_Model($cursor, $this->identities(), $class);
	}

	public function find_one($suffix, $id = NULL)
	{
		if ($id === NULL)
		{
			$id = $suffix;
			$suffix = NULL;
		}
		
		$class = $this->model_class($suffix);

		if ($model = $this->identities()->get($class, $id))
		{
			// We got it
		}
		else
		{
			$options = $this->container()->query_options();
			$object = $this->collection()->findOne($id, $options);

			$model = new $class;
			$model->__object($object);
		}

		return $model;
	}
	
	public function save($model)
	{
		$this->assert_valid_model($model);

		$collection = $this->collection();
		$object = $model->__object();
		$options = $this->container()->query_options();

		if (isset($object->_id))
		{
			$collection->update(array('_id' => $object->_id), $object, $options);
		}
		else
		{
			$collection->insert($object, $options);
		}

		if ( ! $this->identities()->has($model))
		{
			$this->identities()->set($model);
		}
	}

	public function delete($model)
	{
		$collection = $this->collection();

		if ($valid_model = $this->is_valid_model($model))
		{
			$remove = array('_id' => $model->__object()->_id);
		}
		else
		{
			$remove = $model;
		}
		
		$collection->remove($remove, $this->container()->query_options());

		if ($valid_model AND $this->identities()->has($model))
		{
			$this->identities()->unset($model);
		}
	}

}