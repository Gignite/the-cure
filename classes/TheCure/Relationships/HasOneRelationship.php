<?php
/**
 * A relationship between two models
 * 
 * @example
 * 
 *     $relationship = new HasOne('profile', array(
 *         'mapperSuffix' => 'Profile',
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
 * @category    Relationship
 * @category    Attribute
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Relationships;

use TheCure\Exceptions;

use TheCure\Container;

use TheCure\Accessors\TransferObjectAccessor;

use TheCure\Relations\SetRelation;
use TheCure\Relations\DeleteRelation;

use TheCure\Models\Model;

class HasOneRelationship extends HasRelationship
	implements SetRelation, DeleteRelation {

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
		$accessor = new TransferObjectAccessor;
		$object = $accessor->get($model);
		return $this->mapper($container)->findOne(
			$this->where($object),
			$this->modelSuffix());
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
		$accessor = new TransferObjectAccessor;
		$relation_object = $accessor->get($relation);

		if ( ! isset($accessor->get($relation)->_id))
		{
			// If not saved we save the model first
			$this->mapper($container)->save($relation);
		}

		$accessor->get($model)->{$this->name()} =
			$accessor->get($relation)->_id;
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
		$accessor = new TransferObjectAccessor;
		$modelObject = $accessor->get($model);

		if (isset($modelObject->{$this->name()}))
		{
			unset($modelObject->{$this->name()});
			return;
		}

		throw new Exceptions\FieldNotFoundException;
	}

}