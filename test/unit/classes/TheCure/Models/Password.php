<?php
namespace TheCure\Models;

use TheCure\Lists\AttributeList;

use TheCure\Attributes\Field;

use TheCure\Models\MagicModel;

use TheCure\Relationships\BelongsToOneRelationship;

class Password extends MagicModel {
	
	public static function attributes()
	{
		return new AttributeList(
			new Field('password'),
			new BelongsToOneRelationship('account', array(
				'mapperSuffix' => 'Account',
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