<?php
namespace TheCure\Acceptance\Relationships;
/**
 * Test the attributes container
 *
 * @package     TheCure
 * @category    Relationship
 * @category    Acceptance
 * @category    Test
 * @copyright   Gignite, 2012
 * @license     MIT
 * 
 * @group  acceptance
 * @group  relationships
 * @group  relationships.hasone
 * @group  mappers
 * @group  mappers.mongo
 */
use TheCure\Acceptance\Acceptance;
use TheCure\Container;

class HasOne extends Acceptance {

	/**
	 * @dataProvider  provideContainers
	 */
	public function testItShouldWork($container)
	{
		$account = $container->mapper('Account')->model();
		$password = $container->mapper('Password')->model(array('a password'));

		$account->password($password);
		$container->mapper('Account')->save($account);

		// Setting the HasOne relationship
		$account->password($password);
		$container->mapper('Account')->save($account);

		// Getting the HasOne relationship
		$this->assertSame($password, $account->password());
		
		// Getting the BelongsToOne relationship
		$this->assertSame($account, $password->account());

		// Remove the HasOne relationship
		$account->deletePassword();
		$container->mapper('Account')->save($account);

		$this->assertFalse($account->password());
	}

}

