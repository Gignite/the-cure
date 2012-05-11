<?php
/**
 * Remove relation interface
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

interface Remove {

	/**
	 * Remove one Relation from a Collection of relations.
	 * 
	 * @param   Container
	 * @param   Model
	 * @param   Model
	 * @return  void
	 */
	public function remove(Container $container, Model $model, Model $relation);

}