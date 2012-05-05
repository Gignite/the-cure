<?php
namespace Gignite\TheCure\Acceptance\Relationships;

/**
 * @group  acceptance
 * @group  relationships
 * @group  relationships.onetoone
 * @group  mappers.mongo
 */

use Gignite\TheCure\Acceptance\Acceptance;
use Gignite\TheCure\Container;

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
	}

}

