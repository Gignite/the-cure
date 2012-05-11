<?php
namespace TheCure\Specs;
/**
 * Test an iterable collection
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
 * @group  collections.iterable
 */
use TheCure\Collections\Collection;
use TheCure\Collections\Iterable as IterableCollection;

class CollectionIterableTest extends CollectionTest {

	public function testConstruct()
	{
		return new IterableCollection(
			new Collection(array(
				array('id' => 1),
				array('id' => 2),
			)));		
	}

}