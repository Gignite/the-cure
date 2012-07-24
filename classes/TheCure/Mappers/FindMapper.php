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

interface FindMapper {
	
	/**
	 * Find a Collection of records.
	 *
	 *     // Find all records
	 *     $mapper->find();
	 *
	 *     // Find records by condition
	 *     $mapper->find(array('name' => 'Luke'));
	 *
	 *     // Find all records and instantiate with a
	 *     // suffixed class
	 *     $mapper = new Mapper_Mongo_Profile;
	 *     $mapper->find(NULL, 'Artist')->current(); // => Model_Profile_Artist
	 *
	 *     // Find records and instantiate with a
	 *     // suffixed class by condition
	 *     $mapper = new Mapper_Mongo_Profile;
	 *     $collection = $mapper->find(array('name' => 'Luke'), 'Artist')
	 *     $collection->current(); // => Model_Profile_Artist
	 *
	 * @param   array   conditions (optional)
	 * @param   string  class suffix (optional)
	 * @return  Collection
	 */
	public function find(array $where = NULL, $suffix = NULL);

}