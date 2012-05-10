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
		$where = $this->where($model->__object());
		return $this->mapper($container)->find_one($where, $this->model_suffix());
	}

}