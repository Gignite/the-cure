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

	/**
	 * @abstract
	 * @param Container $container
	 * @param           $model
	 * @param           $relation
	 * @return mixed
	 */
	public function add(Container $container, $model, $relation);

}