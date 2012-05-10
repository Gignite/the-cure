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

use Gignite\TheCure\Container;
use Gignite\TheCure\Models\Model;

interface Set {

	/**
	 * Set the one and only relation.
	 * 
	 * @param   Container
	 * @param   Model
	 * @param   Model
	 * @return  void
	 */
	public function set(Container $container, Model $model, Model $relation);

}