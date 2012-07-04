<?php
namespace TheCure\Models;

use TheCure\Accessors\TransferObjectAccessor;

class BankAccount extends \TheCure\Models\Model {

	public function transferMoney(BankAccount $account, $amount)
	{
		$accessor = new TransferObjectAccessor;
		$accessor->get($this)->balance -= $amount;
		$accessor->get($account)->balance += $amount;
	}

}