<?php
/**
 * Add relation interface
 *
 * @package     TheCure
 * @category    Relation
 * @category    Relationship
 * @category    Attribute
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Relations;

use TheCure\Container;
use TheCure\Models\Model;

interface AddRelation {

	/**
	 * Add a $relation to a $model's collection of relations.
	 * 
	 * @param   Container
	 * @param   Model
	 * @param   Model
	 * @return  mixed
	 */
	public function add(Container $container, Model $model, Model $relation);

}