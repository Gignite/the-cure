<?php
namespace Gignite\TheCure\Specs;
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
use Gignite\TheCure\Object;
use Gignite\TheCure\Collections\Model as ModelCollection;

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
				return 'Gignite\TheCure\Models\User';
			});
	}

	/**
	 * @depends  testConstruct
	 */
	public function testCurrent($collection)
	{
		$this->assertInstanceOf(
			'Gignite\TheCure\Models\User',
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