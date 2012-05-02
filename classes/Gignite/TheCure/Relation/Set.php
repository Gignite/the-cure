<?php
/**
 * Set relation interface
 *
 * @package     TheCure
 * @category    Relation
 * @category    Relationships
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure\Relation;

use Gignite\TheCure\Mapper\Container;

interface Set {

	/**
	 * @abstract
	 * @param \Gignite\TheCure\Mapper\Container $container
	 * @param                                   $object
	 * @param                                   $relation
	 * @return mixed
	 */
	public function set(Container $container, $object, $relation);

}