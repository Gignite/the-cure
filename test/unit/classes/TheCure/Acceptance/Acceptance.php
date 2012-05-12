<?php
namespace TheCure\Acceptance;
/**
 * Test the attributes container
 *
 * @package     TheCure
 * @category    Acceptance
 * @category    Test
 * @copyright   Gignite, 2012
 * @license     MIT
 * 
 * @group  acceptance
 */
use TheCure\Container;

abstract class Acceptance extends \PHPUnit_Framework_TestCase {

	public function provideContainers()
	{
		return array(
			array(new Container('Mock')),
			array(new Container('Mongo')),
		);
	}

}