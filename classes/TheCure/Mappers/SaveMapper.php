<?php
/**
 * Find mapper interface
 *
 * @package     TheCure
 * @category    Mapper
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Mappers;

use TheCure\Models\Model;

interface SaveMapper {

	/**
	 * @abstract
	 * @param  Model $model
	 * @return mixed
	 */
	public function save(Model $model);

}