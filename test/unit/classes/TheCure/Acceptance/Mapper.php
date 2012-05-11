<?php
namespace TheCure\Acceptance;

/**
 * @group  acceptance
 * @group  mappers
 * @group  mappers.mongo
 */

use TheCure\IdentityMap;

class Mapper extends Acceptance {

	protected function accountMapper($container)
	{
		$accountMapper = $container->mapper('Account');
		$account = $accountMapper->model();
		$account->email('luke@gignite.com');
		$accountMapper->save($account);
		return $accountMapper;
	}
	
	/**
	 * @dataProvider  provideContainers
	 */
	public function testItShouldFindCollection($container)
	{
		$accountMapper = $this->accountMapper($container);
		
		// We place in new ID map so that we can test other
		// areas of logic
		$accountMapper->identities(new IdentityMap);
		$this->assertInstanceOf(
			'TheCure\Models\Account',
			$accountMapper->find()->current());
	}

	/**
	 * @dataProvider  provideContainers
	 */
	public function testFindShouldAcceptCallableSuffixArg($container)
	{
		$accountMapper = $this->accountMapper($container);
		
		$accounts = $accountMapper->find(NULL, function ()
		{
			return NULL;
		});
		
		$this->assertInstanceOf(
			'TheCure\Models\Account',
			$accounts->current());
	}

	/**
	 * @dataProvider  provideContainers
	 */
	public function testFindOneShouldAcceptCallableSuffixArg($container)
	{
		$accountMapper = $this->accountMapper($container);

		$account = $accountMapper->find_one(NULL, function ()
		{
			return NULL;
		});
		
		$this->assertInstanceOf('TheCure\Models\Account', $account);
	}

}