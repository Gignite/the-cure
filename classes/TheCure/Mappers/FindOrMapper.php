<?php
/**
 * Find collection of models mapper interface
 *
 * @package     TheCure
 * @category    Mapper
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Mappers;

interface FindOrMapper {
	
	/**
	 * Find a single model by ID.
	 * 
	 * @param   array   List of field => values for OR query
	 * @param   string  The model class/callback
	 * @return  mixed   A collection of models or NULL
	 */
	public function findOr($or, $suffix = NULL);

}