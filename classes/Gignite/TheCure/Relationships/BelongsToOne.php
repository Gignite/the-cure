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
		// The fact this where query works for both OneToOne
		// and OneToMany is due to the way Mongo and our Mock
		// mapper match criteria with array fields

		// If a value is an array and you provide a scalar
		// value then we do an in_array() operation
		// If a value isn't an array we just do a simple
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