<?php
namespace Gignite\TheCure\Models;

use Gignite\TheCure\Field;
use Gignite\TheCure\Models\Magic as MagicModel;

use Gignite\TheCure\Relationships\BelongsToOne as BelongsToOneRelationship;

class Password extends MagicModel {
	
	public static function attributes()
	{
		return array(
			new Field('password'),
			new BelongsToOneRelationship('account', array(
				'mapper_suffix' => 'Account',
				'foreign'       => 'password',
			)),
		);
	}

	public function __construct($password)
	{
		$this->__object()->password = md5($password);
	}

}