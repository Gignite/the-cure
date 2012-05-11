<?php
namespace TheCure\Models\User;

use TheCure\Models\Magic as MagicModel;
use TheCure\Field;
use TheCure\Relationships\HasMany;

class Magic extends MagicModel {

	public static function attributes()
	{
		$attributes = parent::attributes();
		$attributes->add(
			new Field('name'),
			new Field('location', array('alias' => 'town')),
			new Field('age',  array('value' => 1)),
			new HasMany('friends', array(
				'mapper_suffix' => 'User',
				// 'model_suffix'  => 'Magic',
			)));
		return $attributes;
	}

}