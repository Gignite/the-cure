<?php
namespace Gignite\TheCure\Models;

use Gignite\TheCure\Attributes;
use Gignite\TheCure\Field;
use Gignite\TheCure\Models\Magic as MagicModel;

use Gignite\TheCure\Relationships\HasOne;

class Account extends MagicModel {
	
	public static function attributes()
	{
		return new Attributes(
			new Field('email'),
			new HasOne('password', array(
				'mapper_suffix' => 'Password',
			)));
	}

}