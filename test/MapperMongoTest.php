<?php
/**
 * Test the persistence of a data object in documents
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Mapper
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
use Gignite\TheCure\IdentityMap;
use Gignite\TheCure\Connections\Mongo as MongoConnection;

class MapperMongoTest extends MapperTest {

	protected static $mapper;

	protected static function config()
	{
		return array(
			'server'     => 'mongodb://127.0.0.1',
			'db'         => 'test',
			'collection' => 'user',
		);
	}

	protected static function db()
	{
		$config = static::config();
		$connection = new Mongo(Arr::get($config, 'server'));

		return $connection->selectDB(Arr::get($config, 'db'));
	}

	protected static function collection($db)
	{
		$config = static::config();
		return $db->selectCollection(Arr::get($config, 'collection'));
	}

	protected static function prepareData()
	{
		$db = static::db();
		$db->drop();
		$collection = static::collection(static::db());
		$data = array('name' => 'Luke');
		$collection->insert($data);
		return (object) $data;
	}

	protected static function mapper()
	{
		if (static::$mapper === NULL)
		{
			$mapper = new Mapper_Mongo_User;
			$mapper->connection(new MongoConnection(static::config()));
			$mapper->identities(new IdentityMap);
			$mapper->config(array('query_options' => array('safe' => TRUE)));
			static::$mapper = $mapper;
		}

		return static::$mapper;
	}

}