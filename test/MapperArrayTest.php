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
class MapperArrayTest extends MapperTest {

	protected static $mapper;

	protected static function mapper()
	{
		if (static::$mapper === NULL)
		{
			$mapper = new Mapper_Array_User;
			$mapper->identities(new IdentityMap);
			static::$mapper = $mapper;
		}

		return static::$mapper;
	}

	protected static function prepareData()
	{
		$mapper = static::mapper();
		$mapper->data[] = $data = (object) array(
			'_id'  => 0,
			'name' => 'Luke',
		);
		return $data;
	}

}