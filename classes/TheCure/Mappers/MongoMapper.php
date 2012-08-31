<?php
/**
 * An MongoDB mapper
 * 
 *     $mapper->find(array('name' => 'Luke'));
 *     $mapper->find(array('name' => 'Luke'), 'Admin');
 *
 * @package     TheCure
 * @category    Mapper
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Mappers;

use TheCure\TransferObjects\TransferObject;

use TheCure\Connections\Connection;

use TheCure\Mapper\ConnectionSetGet;

use TheCure\Mappers\Mapper;

use TheCure\Models\Model;

use MongoID, MongoCursor;

abstract class MongoMapper extends Mapper implements ConnectionSetGet {

	protected $connection;

	protected function idize($id)
	{
		if (is_array($id))
		{
			$first_value = current($id);
			$first_key = key($id);

			if (is_array($first_value))
			{
				foreach ($first_value as $_k => $_id)
				{
					$id[$first_key][$_k] = $this->idize($_id);
				}
			}
		}

		elseif ( ! $id instanceOf MongoID)
		{
			$id = new MongoID($id);
		}

		return $id;
	}

	/**
	 * Sets the connection property if one is passed in otherwise
	 * it returns the connection.
	 *
	 * @param  Connection|null $connection
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
			$this->collectionName());
	}

	/**
	 * Options used in the 2nd argument to the Mongo
	 * remove, insert and update methods.
	 *
	 * @return array
	 */
	protected function queryOptions()
	{
		return $this->config('queryOptions', array());
	}

	private function log($message)
	{
		if (class_exists('Kohana') AND isset(\Kohana::$log))
		{
			\Kohana::$log->add(\Log::INFO, $message);
		}
	}

	public function log_nonindexed_queries(MongoCursor $results)
	{
		if ($config = $this->config('log', FALSE) 
			AND isset($config['nonindexed_queries']))
		{
			$explain = $results->explain();

			if (isset($explain['cursor']) 
				AND $explain['cursor'] === 'BasicCursor')
			{
				$info = $results->info();
				$this->log(compact('info', 'explain'), TRUE);
			}
		}
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
	 * @return Model|Collection
	 */
	public function find(array $where = NULL, $suffix = NULL)
	{
		$collection = $this->collection();

		$mapper = $this;

		return $this->createCollection(
			$where,
			$suffix,
			function ($where) use ($collection, $mapper)
			{
				$cursor = $collection->find($where);
				$mapper->log_nonindexed_queries($cursor);
				return $cursor;
			});
	}

	/**
	 * @example
	 *
	 *   // Find one entry in the Page model
	 *   $container->mapper('Page')->findOne()
	 *
	 *   // When using a suffix this would an entry
	 *   // in the Page\Artist model
	 *   $container->mapper('Page')->findOne(NULL, 'Artist')
	 *
	 *   // When no suffix is needed the where
	 *   // condition can be moved forward.
	 *   $container->mapper('Page')->findOne(array('email' => '...')
	 *
	 * @param  null $suffix
	 * @param  null $where
	 * @return mixed
	 */
	public function findOne($where = NULL, $suffix = NULL)
	{
		$collection = $this->collection();

		return $this->createModel(
			$where,
			$suffix,
			function ($where) use ($collection)
			{
				if ($row = $collection->findOne($where))
				{
					return new TransferObject($row);
				}
			});
	}

	/**
	 * Saves a models data to Mongo
	 *
	 * @param Model $model
	 */
	public function save(Model $model)
	{
		$collection = $this->collection();
		$options = $this->queryOptions();

		$this->saveModel(
			$model,
			function ($object) use ($collection, $options)
			{
				$array = $object->asArray();

				if (isset($object->_id))
				{
					$options['upsert'] = TRUE;

					$report = $collection->update(
						array('_id' => $object->_id),
						$array,
						$options);
				}
				else
				{
					$collection->insert($array, $options);
				}

				return new TransferObject($array);
			});
	}

	/**
	 * @param $model
	 */
	public function delete($model)
	{
		$collection = $this->collection();
		$options = $this->queryOptions();

		$this->deleteModel(
			$model,
			function ($where) use ($collection, $options)
			{
				$collection->remove($where, $options);
			});
	}

}