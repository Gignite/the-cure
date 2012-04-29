<?php
/**
 * Test an iterable domain collection
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Collection
 * @category    Domain
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
use Gignite\TheCure\IdentityMap;
use Gignite\TheCure\Collections\Model as ModelCollection;

class CollectionModelTest extends CollectionTest {

	public function testConstruct()
	{
		return new ModelCollection(
			new ArrayIterator(array(
				(object) array('_id' => 0),
				(object) array('_id' => 1),
			)),
			$this->map = new IdentityMap,
			'Model_User');
	}

	/**
	 * @depends  testConstruct
	 */
	public function testCurrent($collection)
	{
		$this->assertInstanceOf('Model_User', $collection->current());
		return $collection;
	}

	/**
	 * @depends  testConstruct
	 */
	public function testCurrentReturnsSameObject($collection)
	{
		$this->assertSame($collection->current(), $collection->current());
	}

}