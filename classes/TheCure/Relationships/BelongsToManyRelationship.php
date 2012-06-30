<?php
/**
 * A relationship between two models (inverse relationship)
 *
 * @package     TheCure
 * @category    Relationship
 * @category    Attribute
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Relationships;

use TheCure\Container;
use TheCure\ObjectAccessor;
use TheCure\Relation;
use TheCure\Models\Model;

class BelongsToManyRelationship extends BelongsToRelationship {

	/**
	 * Find a Collection of relations.
	 * 
	 * @param   Container
	 * @param   Model
	 * @return  Collection
	 */
	public function find(Container $container, Model $model)
	{
		$accessor = new ObjectAccessor;
		$object = $accessor->get($model);
		$where = $this->where($object);
		return $this->mapper($container)->find($where, $this->modelSuffix());
	}

}