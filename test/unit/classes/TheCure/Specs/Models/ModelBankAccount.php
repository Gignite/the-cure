<?php
namespace TheCure\Specs;
/**
 * Test a bank account model
 * 
 * This test covers logic found within a README example.
 *
 * @package     TheCure
 * @category    Model
 * @category    Spec
 * @category    Test
 * @copyright   Gignite, 2012
 * @license     MIT
 * 
 * @group  specs
 * @group  models
 * @group  models.bankaccount
 */

use TheCure\Models;
use TheCure\ObjectAccessor;

class ModelBankAccount extends \PHPUnit_Framework_TestCase {

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
		$accessor = new ObjectAccessor;
		$accessor->get($lukesAccount)->balance = 100;
		$accessor->get($bobsAccount)->balance = 0;

		$lukesAccount->transfer_money($bobsAccount, 100);
		
		$this->assertSame(0, $accessor->get($lukesAccount)->balance);
		$this->assertSame(100, $accessor->get($bobsAccount)->balance);
	}

}
