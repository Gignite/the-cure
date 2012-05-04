<?php
namespace Gignite\TheCure\Acceptance\Relationships;

/**
 * @group  acceptance
 * @group  relationships
 * @group  relationships.onetoone
 */

use Gignite\TheCure\Acceptance\Acceptance;
use Gignite\TheCure\Mapper\Container;

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

		// Test OneToOne
		$this->assertSame($password, $account->password());

		// Test BelongsToOne
		$this->assertSame($account, $password->account());
	}

}

