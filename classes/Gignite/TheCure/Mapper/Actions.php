<?php
/**
 * An interface for a mapper's actions
 *
 * @package     TheCure
 * @category    Mapper
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure\Mapper;

use Gignite\TheCure\Models\Model;

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
	 *     $mapper->find('Artist')->current(); // => Model_Profile_Artist
	 *
	 *     // Find records and instantiate with a
	 *     // suffixed class by condition
	 *     $mapper = new Mapper_Mongo_Profile;
	 *     $collection = $mapper->find('Artist', array('name' => 'Luke'))
	 *     $collection->current(); // => Model_Profile_Artist
	 *
	 * @param   string  class suffix (optional)
	 * @param   array   conditions (optional)
	 * @return  Collection
	 */
	public function find($suffix = NULL, array $where = NULL);

	/**
	 * @abstract
	 * @param null $suffix
	 * @param null $where
	 * @return mixed
	 */
	public function find_one($suffix = NULL, $where = NULL);

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