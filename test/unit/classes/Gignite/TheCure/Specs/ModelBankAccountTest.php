<?php
namespace Gignite\TheCure\Specs;

use Gignite\TheCure\Models;

class ModelBankAccountTest extends \PHPUnit_Framework_TestCase {

	public function provideBankAccounts()
	{
		return array(
			array(new Models\BankAccount, new Models\BankAccount),
		);
	}
	
	/**
	 * @dataProvider  provideBankAccounts
	 */
	public function testItShouldTransferMoneyFromOneAccountToAnother(
		$lukesAccount,
		$bobsAccount)
	{
		$lukesAccount->__object()->balance = 100;
		$bobsAccount->__object()->balance = 0;

		$lukesAccount->transfer_money($bobsAccount, 100);
		
		$this->assertSame(0, $lukesAccount->__object()->balance);
		$this->assertSame(100, $bobsAccount->__object()->balance);
	}

}
