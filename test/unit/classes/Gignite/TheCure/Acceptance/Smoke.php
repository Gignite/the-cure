<?php
namespace Gignite\TheCure\Acceptance;

/**
 * @group  acceptance
 * @group  smoke
 * @group  mappers.mongo
 */

use Gignite\TheCure\Container;
use Gignite\TheCure\Models\User\Magic as MagicUserModel;

class Smoke extends Acceptance {

	/**
	 * @dataProvider  provideContainers
	 */
	public function testItShouldWork($container)
	{
		$user = $container->mapper('User')->model('Magic');
		$user->name($expectedName = 'Luke');
		$this->assertSame($expectedName, $user->name());

		$bob = $container->mapper('User')->model('Magic');
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