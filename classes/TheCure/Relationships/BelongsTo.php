<?php
/**
 * A relationship from child(ren) to parent(s)
 *
 * @package     TheCure
 * @category    Relationship
 * @category    Attribute
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Relationships;

use TheCure\Container;
use TheCure\Relation;

abstract class BelongsTo extends Relationship implements Relation\Find {

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

}