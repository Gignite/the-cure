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
namespace TheCure\Relations;

use TheCure\Container;
use TheCure\Models\Model;

interface FindRelation {

	/**
	 * Find a Collection of relations or a single relation.
	 * 
	 * @param   Container
	 * @param   Model
	 * @return  Collection|Model
	 */
	public function find(Container $container, Model $model);

}