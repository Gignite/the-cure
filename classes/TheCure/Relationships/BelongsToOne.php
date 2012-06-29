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

class BelongsToOne extends BelongsTo {

	/**
	 * Find a single relation.
	 * 
	 * @param   Container
	 * @param   Model
	 * @return  Model
	 */
	public function find(Container $container, Model $model)
	{
		$accessor = new ObjectAccessor;
		$object = $accessor->get($model);
		$where = $this->where($object);
		return $this->mapper($container)->findOne($where, $this->modelSuffix());
	}

}