<?php
namespace Gignite\TheCure\Models\User;

use Gignite\TheCure\Models\Magic as MagicModel;
use Gignite\TheCure\Field;
use Gignite\TheCure\Relationships\HasMany;

class Magic extends MagicModel {

	public static function attributes()
	{
		return parent::attributes() + array(
			new Field('name'),
			new Field('location', array('alias' => 'town')),
			new Field('age',  array('value' => 1)),
			new HasMany('friends', array(
				'mapper_suffix' => 'User',
				// 'model_suffix'  => 'Magic',
			)),
		);
	}

}