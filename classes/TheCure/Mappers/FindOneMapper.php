<?php
/**
 * Find one model mapper interface
 *
 * @package     TheCure
 * @category    Mapper
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Mappers;

interface FindOneMapper {
	
	/**
	 * Find a single model by ID.
	 * 
	 * @param   mixed   ID in whatever form the persistence
	 *                  layer takes
	 * @param   string  The model class/callback
	 * @return  mixed   A single model or NULL
	 */
	public function findOneByID($id, $suffix = NULL);

}