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
	 * @param Container $container
	 * @param           $model
	 * @param           $relation
	 * @return mixed
	 */
	public function set(Container $container, $model, $relation);

}