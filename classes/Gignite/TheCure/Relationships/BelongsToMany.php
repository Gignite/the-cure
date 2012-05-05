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

class BelongsToMany extends BelongsTo {

	/**
	 * @param  Container $container
	 * @param  $id
	 * @return mixed
	 */
	public function find(Container $container, $model)
	{
		$where = $this->where($model->__object());
		return $this->mapper($container)->find($this->model_suffix(), $where);
	}

}