<?php

namespace Models;

class BankAccount extends \Gignite\TheCure\Models\Model {

	public function transfer_money(BankAccount $account, $amount)
	{
		$this->__object()->balance -= $amount;
		$account->__object()->balance += $amount;
	}

}