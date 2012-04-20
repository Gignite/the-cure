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

	protected $table = 'test';

	public function testInstance()
	{
		return new Mapper_Array(
			array(
				'table'  => $this->table,
				'fields' => array('name'),
			),
			array(
				array('id' => 0, 'name' => 'Luke'),
				array('id' => 1, 'name' => 'Peter'),
			));
	}

	public function testInsertNonAuto()
	{
		$mapper = new Mapper_Array(
			array(
				'table'  => $this->table,
				'fields' => array('name'),
				'auto'   => FALSE,
			));

		$expected = array('name' => 'Luke', 'id' => 0);
		$id = $mapper->insert(new Object($expected));
		$rows = $mapper->as_array();
		$this->assertSame($expected, $rows[$id]);

		return $mapper;
	}

}