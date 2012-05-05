<?php
namespace Gignite\TheCure\Acceptance;

/**
 * @group  acceptance
 * @group  mappers
 */

use Gignite\TheCure\IdentityMap;

class Mappers extends Acceptance {
	
	/**
	 * @dataProvider  provideContainers
	 */
	public function testItShouldFindCollection($container)
	{
		$accountMapper = $container->mapper('Account');
		$account = $accountMapper->model();
		$account->email('luke@gignite.com');
		$accountMapper->save($account);

		// We place in new ID map so that we can test other
		// areas of logic
		$accountMapper->identities(new IdentityMap);
		$this->assertInstanceOf(
			'Gignite\TheCure\Models\Account',
			$accountMapper->find()->current());
	}

}
