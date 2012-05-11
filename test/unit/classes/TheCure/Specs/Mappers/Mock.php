<?php
namespace TheCure\Specs;
/**
 * Test mock (array) mapper
 *
 * @package     TheCure
 * @category    Spec
 * @category    Test
 * @copyright   Gignite, 2012
 * @license     MIT
 * 
 * @group  specs
 * @group  mappers
 * @group  mappers.mock
 */
use TheCure\Factory;
use TheCure\IdentityMap;
use TheCure\Object;
use TheCure\Mappers\Mock\User as MockUserMapper;

class MapperMockTest extends MapperTest {

	protected static $mapper;

	protected static function mapper()
	{
		if (static::$mapper === NULL)
		{
			$mapper = new MockUserMapper;
			$mapper->identities(new IdentityMap);
			$mapper->factory(
				new Factory(\Kohana::$config->load('the-cure.factory')));
			static::$mapper = $mapper;
		}

		return static::$mapper;
	}

	protected static function prepareData()
	{
		$mapper = static::mapper();
		$mapper->data[] = $data = new Object(array(
			'_id'  => 0,
			'name' => 'Luke',
		));
		return $data;
	}

}