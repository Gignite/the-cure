<?php
/**
 * Find collection of modelsb mapper interface
 *
 * @package     TheCure
 * @category    Mapper
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Mappers;

interface FindInMapper {
	
	/**
	 * Find a collection of models by ID.
	 * 
	 * @param   string  Field name
	 * @param   array   List of values that should match
	 * @param   string  The model class/callback
	 * @return  mixed   A collection of models or NULL
	 */
	public function findIn($field, array $in, $suffix = NULL);

}