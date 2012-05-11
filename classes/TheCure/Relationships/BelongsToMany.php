<?php
/**
 * A relationship between two models (inverse relationship)
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

class BelongsToMany extends BelongsTo {

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

}