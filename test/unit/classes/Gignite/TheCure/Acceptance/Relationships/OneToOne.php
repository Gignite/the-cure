<?php
namespace Gignite\TheCure\Acceptance\Relationships;

/**
 * @group  acceptance
 * @group  relationships
 * @group  relationships.onetoone
 */

use Gignite\TheCure\Mapper\Container;

use Gignite\TheCure\Models\Account;
use Gignite\TheCure\Models\Password;


class OneToOne extends \PHPUnit_Framework_TestCase {

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
		$account = new Account;
		$account->__container($container);
		
		$password = new Password('a password');
		$password->__container($container);

		$account->password($password);
		$container->mapper('Account')->save($account);

		// Test OneToOne
		$this->assertSame($password, $account->password());

		// Test BelongsToOne
		$this->assertSame($account, $password->account());
	}

}

