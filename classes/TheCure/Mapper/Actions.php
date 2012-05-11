<?php
/**
 * An interface for a mapper's actions
 *
 * @package     TheCure
 * @category    Mapper
 * @copyright   Gignite, 2012
 */
namespace TheCure\Mapper;

use TheCure\Models\Model;

interface Actions {
	
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

	/**
	 * @abstract
	 * @param null $where
	 * @param null $suffix
	 * @return mixed
	 */
	public function find_one($where = NULL, $suffix = NULL);

	/**
	 * @abstract
	 * @param  Model $model
	 * @return mixed
	 */
	public function save(Model $model);

	/**
	 * @abstract
	 * @param  $model
	 * @return mixed
	 */
	public function delete($model);

}