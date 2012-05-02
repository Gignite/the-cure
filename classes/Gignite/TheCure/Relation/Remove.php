<?php
/**
 * Remove relation interface
 *
 * @package     TheCure
 * @category    Relation
 * @category    Relationships
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure\Relation;

use Gignite\TheCure\Mapper\Container;

interface Remove {

	/**
	 * @abstract
	 * @param \Gignite\TheCure\Mapper\Container $container
	 * @param                                   $object
	 * @param                                   $relation
	 * @return mixed
	 */
	public function remove(Container $container, $object, $relation);

}