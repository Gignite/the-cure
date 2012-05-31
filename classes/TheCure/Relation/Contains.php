<?php
/**
 * Find relation interface
 *
 * @package     TheCure
 * @category    Relation
 * @category    Relationship
 * @category    Attribute
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Relation;

use TheCure\Container;
use TheCure\Models\Model;

interface Contains {

	/**
	 * Determine if a Model is contained within a relation.
	 * 
	 * @param   Container
	 * @param   Model
	 * @param   Model
	 * @return  boolean
	 */
	public function contains(
		Container $container,
		Model $model,
		Model $relation);

}