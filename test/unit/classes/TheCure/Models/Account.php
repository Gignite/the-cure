<?php
namespace TheCure\Models;

use TheCure\Attributes;
use TheCure\Field;
use TheCure\Models\Magic as MagicModel;

use TheCure\Relationships\HasOne;

class Account extends MagicModel {
	
	public static function attributes()
	{
		return new Attributes(
			new Field('email'),
			new HasOne('password', array(
				'mapperSuffix' => 'Password',
			)));
	}

}