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

use Gignite\TheCure\Container;

interface Remove {

	/**
	 * @abstract
	 * @param Container $container
	 * @param           $model
	 * @param           $relation
	 * @return mixed
	 */
	public function remove(Container $container, $model, $relation);

}