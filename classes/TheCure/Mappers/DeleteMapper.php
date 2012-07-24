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

interface DeleteMapper {

	/**
	 * @abstract
	 * @param  $model
	 * @return mixed
	 */
	public function delete($model);
	
}