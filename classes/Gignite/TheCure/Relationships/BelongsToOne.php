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

use Gignite\TheCure\Mapper\Container;
use Gignite\TheCure\Relation;

class BelongsToOne extends Relationship implements Relation\Find {

	protected $foreign;

	protected function foreign()
	{
		return $this->foreign;
	}

	protected function where($object)
	{
		return array($this->foreign() => $object->_id);
	}

	/**
	 * @param  Container $container
	 * @param  $id
	 * @return mixed
	 */
	public function find(Container $container, $model)
	{
		$where = $this->where($model->__object());
		return $this->mapper($container)->find_one($this->model_suffix(), $where);
	}

}