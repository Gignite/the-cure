<?php
namespace TheCure\Models;

use TheCure\ObjectAccessor;

class BankAccount extends \TheCure\Models\Model {

	public function transfer_money(BankAccount $account, $amount)
	{
		$accessor = new ObjectAccessor;
		$accessor->get($this)->balance -= $amount;
		$accessor->get($account)->balance += $amount;
	}

}