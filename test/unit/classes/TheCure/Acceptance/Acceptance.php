<?php
namespace TheCure\Acceptance;

/**
 * @group  acceptance
 * @group  smoke
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