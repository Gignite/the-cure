<?php
namespace TheCure\Specs;
/**
 * Test an iterable model collection
 *
 * @package     TheCure
 * @category    Collection
 * @category    Spec
 * @category    Test
 * @copyright   Gignite, 2012
 * @license     MIT
 * 
 * @group  specs
 * @group  collections
 * @group  collections.model
 */
use TheCure\IdentityMap;
use TheCure\Object;
use TheCure\Collections\Model as ModelCollection;

class CollectionModelTest extends CollectionTest {

	public function testConstruct()
	{
		return new ModelCollection(
			new \ArrayIterator(array(
				new Object(array('_id' => 0)),
				new Object(array('_id' => 1)),
			)),
			$this->map = new IdentityMap,
			function ()
			{
				return 'TheCure\Models\User';
			});
	}

	/**
	 * @depends  testConstruct
	 */
	public function testCurrent($collection)
	{
		$this->assertInstanceOf(
			'TheCure\Models\User',
			$collection->current());
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