<?php
namespace Gignite\TheCure\Specs;
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
 * 
 * @group  mappers
 * @group  mappers.mongo
 * @group  spec
 */
use Gignite\TheCure\Factory;
use Gignite\TheCure\IdentityMap;
use Gignite\TheCure\Object;
use Gignite\TheCure\Mappers;
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
		$connection = new \Mongo(\Arr::get($config, 'server'));
		return $connection->selectDB(\Arr::get($config, 'db'));
	}

	protected static function collection($db)
	{
		$config = static::config();
		return $db->selectCollection(\Arr::get($config, 'collection'));
	}

	protected static function prepareData()
	{
		if (class_exists('Mongo'))
		{
			$db = static::db();
			$db->drop();
			$collection = static::collection(static::db());
			$data = array('name' => 'Luke');
			$collection->insert($data);
			return new Object($data);
		}
	}

	protected static function mapper()
	{
		if (static::$mapper === NULL)
		{
			$mapper = new Mappers\Mongo\User;
			$mapper->connection(new MongoConnection(static::config()));
			$mapper->identities(new IdentityMap);
			$mapper->factory(
				new Factory(\Kohana::$config->load('thecure.factory')));
			$mapper->config(array('query_options' => array('safe' => TRUE)));
			static::$mapper = $mapper;
		}

		return static::$mapper;
	}

}