<?php
namespace Gignite\TheCure\Models;

use Gignite\TheCure\Field;
use Gignite\TheCure\Models\Magic as MagicModel;

use Gignite\TheCure\Relationships\OneToOne as OneToOneRelationship;

class Account extends MagicModel {
	
	public static function attributes()
	{
		return array(
			new OneToOneRelationship('password', array(
				'mapper_suffix' => 'Password',
			)),
		);
	}

}