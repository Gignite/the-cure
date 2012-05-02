<?php
/**
 * Find relation interface
 *
 * @package     TheCure
 * @category    Relation
 * @category    Relationships
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure\Relation;

use Gignite\TheCure\Mapper\Container;

interface Find {

	/**
	 * @abstract
	 * @param  Container $container
	 * @param  $value
	 * @return mixed
	 */
	public function find(Container $container, $model, $value);

}