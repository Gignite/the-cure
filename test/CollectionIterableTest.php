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
class CollectionIterableTest extends CollectionTest {

	public function testConstruct()
	{
		return new Collection_Iterable(
			new Collection(array(
				array('id' => 1),
				array('id' => 2),
			)));		
	}

}