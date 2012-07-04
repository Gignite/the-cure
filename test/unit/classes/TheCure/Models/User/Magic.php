<?php
namespace TheCure\Models\User;

use TheCure\Models\MagicModel;
use TheCure\Attributes\Field;
use TheCure\Relationships\HasManyRelationship;

class Magic extends MagicModel {

	public static function attributes()
	{
		$attributes = parent::attributes();
		$attributes->add(
			new Field('name'),
			new Field('location', array('alias' => 'town')),
			new Field('age',  array('value' => 1)),
			new HasManyRelationship('friends', array(
				'mapperSuffix' => 'User',
				// 'modelSuffix'  => 'Magic',
			)));
		return $attributes;
	}

}