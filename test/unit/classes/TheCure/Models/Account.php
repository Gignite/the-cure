<?php
namespace TheCure\Models;

use TheCure\Lists\AttributeList;

use TheCure\Attributes\Field;

use TheCure\Models\MagicModel;

use TheCure\Relationships\HasOneRelationship;

class Account extends MagicModel {
	
	public static function attributes()
	{
		return new AttributeList(
			new Field('email'),
			new HasOneRelationship('password', array(
				'mapper' => 'Password',
			)));
	}

}