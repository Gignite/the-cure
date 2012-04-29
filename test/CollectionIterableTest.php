<?php
/**
 * Test an iterable collection
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Collection
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
use Gignite\TheCure\Collections\Collection;
use Gignite\TheCure\Collections\Iterable as IterableCollection;

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