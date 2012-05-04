<?php
namespace Gignite\TheCure\Acceptance;

/**
 * @group  acceptance
 * @group  smoke
 */

use Gignite\TheCure\Mapper\Container;

abstract class Acceptance extends \PHPUnit_Framework_TestCase {

	public function provideContainers()
	{
		return array(
			array(new Container('Mock')),
			array(new Container('Mongo')),
		);
	}

}