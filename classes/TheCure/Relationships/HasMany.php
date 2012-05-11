<?php
/**
 * A relationship between one and many models
 *
 * @package     TheCure
 * @category    Attribute
 * @category    Relationships
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Relationships;

use TheCure\Container;
use TheCure\Relation;
use TheCure\Models\Model;

class HasMany extends Has implements Relation\Add, Relation\Remove {

	protected function where($object)
	{
		return array(
			'_id' => array('$in' => $object->{$this->name()}),
		);
	}

	/**
	 * Find a Collection of relations.
	 * 
	 * @param   Container
	 * @param   Model
	 * @return  Collection
	 */
	public function find(Container $container, Model $model)
	{
		$where = $this->where($model->__object());
		return $this->mapper($container)->find($where, $this->model_suffix());
	}

	/**
	 * Add a $relation to a $model's collection of relations.
	 * 
	 * @param   Container
	 * @param   Model
	 * @param   Model
	 * @return  mixed
	 */
	public function add(Container $container, Model $model, Model $relation)
	{
		$relation_object = $relation->__object();

		if ( ! isset($relation_object->_id))
		{
			// If not saved we save the model first
			$this->mapper($container)->save($relation);
		}

		$object = $model->__object();

		if (isset($object->{$this->name()}))
		{
			$relations = $object->{$this->name()};
		}
		else
		{
			$relations = array();
		}

		$relations[] = $relation->__object()->_id;
		$object->{$this->name()} = $relations;
	}

	/**
	 * Remove one Relation from a Collection of relations.
	 * 
	 * @param   Container
	 * @param   Model
	 * @param   Model
	 * @return  void
	 */
	public function remove(Container $container, Model $model, Model $relation)
	{
		$model_object = $model->__object();

		if (isset($model_object->{$this->name()}))
		{
			$ids = $model_object->{$this->name()};
		
			foreach ($ids as $_k => $_id)
			{
				if ($_id == $relation->__object()->_id)
				{
					$relations = $model_object->{$this->name()};
					unset($relations[$_k]);
					$model_object->{$this->name()} = $relations;
					return;
				}
			}
			
			throw new Relation\NotFoundException;
		}

		throw new Relation\FieldNotFoundException;
	}

}