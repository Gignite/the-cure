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

interface FindOneMapper {
	
	/**
	 * Find a collection of models by ID.
	 * 
	 * @param   array   List of field => values for AND query
	 * @param   string  The model class/callback
	 * @return  mixed   A collection of models or NULL
	 */
	public function findAnd($and, $suffix = NULL);

}