<?php
/**
 * Remove relation interface
 *
 * @package     TheCure
 * @category    Relation
 * @category    Relationships
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Relation;

use TheCure\Container;
use TheCure\Models\Model;

interface Delete {

	/**
	 * Delete the one and only relation from a model.
	 * 
	 * @param   Container
	 * @param   Model
	 * @return  void
	 */
	public function delete(Container $container, Model $model);

}