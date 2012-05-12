<?php
namespace TheCure\Models;

use TheCure\Attributes;
use TheCure\Field;
use TheCure\Models\Magic as MagicModel;

use TheCure\Relationships\BelongsToOne;

class Password extends MagicModel {
	
	public static function attributes()
	{
		return new Attributes(
			new Field('password'),
			new BelongsToOne('account', array(
				'mapper_suffix' => 'Account',
				'foreign'       => 'password',
			)));
	}

	public function __construct($password)
	{
		$this->password($password);
	}

	public function password($password = NULL)
	{
		if ($password !== NULL)
		{
			$password = md5($password);
		}

		return parent::password($password);
	}

}