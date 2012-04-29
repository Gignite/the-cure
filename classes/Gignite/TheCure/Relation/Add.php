<?php
/**
 * Add relation interface
 *
 * @package     TheCure
 * @category    Relation
 * @category    Relationships
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure\Relation;

use Gignite\TheCure\Mapper\Container;

interface Add {
	
	public function add(Container $container, $object, $relation);

}