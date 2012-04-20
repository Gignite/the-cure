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
class MapperMongoTest extends MapperTest {

	protected static function config()
	{
		return array(
			'server'     => 'mongodb://localhost',
			'db'         => 'test',
			'collection' => 'user',
		);
	}

	public static function db()
	{
		$config = static::config();
		$connection = new Mongo(Arr::get($config, 'server'));

		return $connection->selectDB(Arr::get($config, 'db'));
	}

	public static function collection($db)
	{
		$config = static::config();
		return $db->selectCollection(Arr::get($config, 'collection'));
	}

	public static function setUpBeforeClass()
	{
		$db = static::db();
		$db->drop();

		$collection = static::collection($db);
		$collection->insert(array('name' => 'Luke'));
	}

	public function mapper()
	{
		$mapper = new Mapper_Mongo_User;
		$mapper->connection(new Connection_Mongo(static::config()));
		$mapper->identities(new IdentityMap);
		$mapper->config(array(
			// 'query_options' => array('safe' => TRUE),
		));
		return $mapper;
	}

}