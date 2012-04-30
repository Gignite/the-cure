<?php
/**
 * Test the mock persistence of a data object
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Mapper
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
use Gignite\TheCure\Factory;
use Gignite\TheCure\IdentityMap;
use Gignite\TheCure\Object;

class MapperMockTest extends MapperTest {

	protected static $mapper;

	protected static function mapper()
	{
		if (static::$mapper === NULL)
		{
			$mapper = new Mappers\Mock\User;
			$mapper->identities(new IdentityMap);
			$mapper->factory(
				new Factory(Kohana::$config->load('thecure.factory')));
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