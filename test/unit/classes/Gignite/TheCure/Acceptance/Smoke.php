<?php
namespace Gignite\TheCure\Acceptance;

use Gignite\TheCure\Mapper\Container;
use Gignite\TheCure\Models\User\Magic as MagicUserModel;

class Smoke extends \PHPUnit_Framework_TestCase {

	public function provideContainers()
	{
		return array(
			array(new Container('Mock')),
			array(new Container('Mongo')),
		);
	}

	/**
	 * @dataProvider  provideContainers
	 */
	public function testItShouldWork($container)
	{
		$user = new MagicUserModel;
		$user->__container($container);

		$user->name($expectedName = 'Luke');
		$this->assertSame($expectedName, $user->name());

		$bob = new MagicUserModel;
		$bob->name($expectedFriendName = 'Bob');
		$this->assertSame($expectedFriendName, $bob->name());

		$user->add_friends($bob);
		$this->assertSame(1, $user->friends()->count());

		$user->remove_friends($bob);
		$this->assertSame(0, $user->friends()->count());

		$object = new \Gignite\TheCure\Object;
		$object->set($expectedArray = array(
			'name' => 'Jim',
			'age'  => 26,
		));
		$this->assertSame($expectedArray, $object->get(array('name', 'age')));
	}

}