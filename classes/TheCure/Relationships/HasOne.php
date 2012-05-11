<?php
/**
 * A relationship between two models
 * 
 * @example
 * 
 *     $relationship = new HasOne('profile', array(
 *         'mapper_suffix' => 'Profile',
 *     ));
 *     
 *     $container = new Container('Mock');
 *     $user = $container->mapper('User')->model();
 *     
 *     // Find a user's profile
 *     $profile = $relationship->find($container, $user);
 *     
 *     // Set a user's profile
 *     $profile = $container->mapper('Profile')->model();
 *     $relationship->set($container, $user, $profile);
 *     
 *     // Delete a user's profile
 *     $relationship->delete($container, $user);
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

class HasOne extends Has implements Relation\Set, Relation\Delete {

	protected function where($object)
	{
		return array('_id' => $object->{$this->name()});
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
	 * Delete the one and only relation from a model.
	 * 
	 * @param   Container
	 * @param   Model
	 * @return  void
	 */
	public function delete(Container $container, Model $model)
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