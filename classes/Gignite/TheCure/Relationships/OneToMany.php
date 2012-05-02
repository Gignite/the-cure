<?php
/**
 * A relationship between one and many models
 *
 * @package     TheCure
 * @category    Field
 * @category    Relationships
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure\Relationships;

use Gignite\TheCure\Mapper\Container;
use Gignite\TheCure\Relation;

class OneToMany	extends Relationship implements Relation\Add, Relation\Remove {

	/**
	 * @param  Container $container
	 * @param  $ids
	 * @return mixed
	 */
	public function find(Container $container, $ids)
	{
		return $this->mapper($container)->find($this->model_suffix(), array(
			'_id' => array('$in' => $ids),
		));
	}

	/**
	 * @param  Container $container
	 * @param  $model
	 * @param  $relation
	 */
	public function add(Container $container, $model, $relation)
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
	 * @param  Container $container
	 * @param  $model
	 * @param  $relation
	 * @return mixed
	 * @throws Relation\NotFoundException
	 * @throws Relation\FieldNotFoundException
	 */
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