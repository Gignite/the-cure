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
 * @group  mappers
 * @group  mappers.mongo
 */
use TheCure\Maps\IdentityMap;

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

		$account = $accountMapper->findOne(NULL, function ()
		{
			return NULL;
		});
		
		$this->assertInstanceOf('TheCure\Models\Account', $account);
	}

}
