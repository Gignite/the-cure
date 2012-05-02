<?php
/**
 * An MongoDB mapper
 * 
 *     $mapper->find($id);
 *     $mapper->find(array('name' => 'Luke'));
 *     $mapper->find('Admin', array('name' => 'Luke'));
 *
 * @package     TheCure
 * @category    Mappers
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure\Mappers;

use Gignite\TheCure\Object;
use Gignite\TheCure\Connections\Connection;
use Gignite\TheCure\Mapper\ConnectionSetGet;
use Gignite\TheCure\Mappers\Mapper;
use Gignite\TheCure\Models\Model;

abstract class Mongo extends Mapper implements ConnectionSetGet {

	protected $connection;

	/**
	 * Sets the connection property if one is passed in otherwise
	 * it returns the connection.
	 *
	 * @param  \Gignite\TheCure\Connections\Connection|null $connection
	 * @return \Mongo
	 */
	public function connection(Connection $connection = NULL)
	{
		if ($connection === NULL)
		{
			return $this->connection;
		}
		
		$this->connection = $connection;
	}

	/**
	 * @return \MongoCollection
	 */
	protected function collection()
	{
		return $this->connection()->get()->selectCollection(
			$this->collection_name());
	}

	/**
	 * Options used in the 2nd argument to the Mongo
	 * remove, insert and update methods.
	 *
	 * @return array
	 */
	protected function query_options()
	{
		return $this->config('query_options', array());
	}

	/**
	 * @example
	 *
	 *   // Find all entries in the Page model
	 *   $container->mapper('Page')->find()
	 *
	 *   // When using a suffix this would find all
	 *   // the entries for the Page\Artist model
	 *   $container->mapper('Page')->find('Artist')
	 *
	 *   // When no suffix is needed the where
	 *   // condition can be moved forward.
	 *   $container->mapper('Page')->find(array('email' => '...')
	 *
	 * @param  null $suffix
	 * @param  array|null $where
	 * @return \Gignite\TheCure\Collections\Model|\Gignite\TheCure\Mapper\Collection
	 */
	public function find($suffix = NULL, array $where = NULL)
	{
		$collection = $this->collection();

		return $this->create_collection(
			$suffix,
			$where,
			function ($where) use ($collection)
			{
				return $collection->find($where);
			});
	}

	/**
	 * @example
	 *
	 *   // Find one entry in the Page model
	 *   $container->mapper('Page')->find_one()
	 *
	 *   // When using a suffix this would an entry
	 *   // in the Page\Artist model
	 *   $container->mapper('Page')->find_one('Artist')
	 *
	 *   // When no suffix is needed the where
	 *   // condition can be moved forward.
	 *   $container->mapper('Page')->find_one(array('email' => '...')
	 *
	 * @param  null $suffix
	 * @param  null $where
	 * @return mixed
	 */
	public function find_one($suffix = NULL, $where = NULL)
	{
		$collection = $this->collection();

		return $this->create_model(
			$suffix,
			$where,
			function ($where) use ($collection)
			{
				return new Object($collection->findOne($where));
			});
	}

	/**
	 * Saves a models data to Mongo
	 *
	 * @param \Gignite\TheCure\Models\Model $model
	 */
	public function save(Model $model)
	{
		$collection = $this->collection();
		$options = $this->query_options();

		$this->save_model(
			$model,
			function ($object) use ($collection, $options)
			{
				$array = $object->as_array();

				if (isset($object->_id))
				{
					$collection->update(
						array('_id' => $object->_id),
						$array,
						$options);
				}
				else
				{
					$collection->insert($array, $options);
				}

				return new Object($array);
			});
	}

	/**
	 * @param $model
	 */
	public function delete($model)
	{
		$collection = $this->collection();
		$options = $this->query_options();

		$this->delete_model(
			$model,
			function ($where) use ($collection, $options)
			{
				$collection->remove($where, $options);
			});
	}

}