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
 * @group  smoke
 * @group  mappers
 * @group  mappers.mongo
 */
use TheCure\Container;
use TheCure\TransferObjects\TransferObject;

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

		$user->addFriends($bob);
		$this->assertSame(1, $user->friends()->count());

		$user->removeFriends($bob);
		$this->assertSame(0, $user->friends()->count());

		$object = new TransferObject($expectedArray = array(
			'name' => 'Jim',
			'age'  => 26,
		));
		$this->assertSame($expectedArray, $object->get(array('name', 'age')));
	}

}