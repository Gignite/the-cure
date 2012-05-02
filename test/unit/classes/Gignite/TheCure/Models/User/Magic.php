<?php
namespace Gignite\TheCure\Models\User;

use Gignite\TheCure\Models\Magic as MagicModel;
use Gignite\TheCure\Field;
use Gignite\TheCure\Relationships\OneToMany;

class Magic extends MagicModel {

	public static function fields()
	{
		return parent::fields() + array(
			new Field('name'),
			new Field('location', array('alias' => 'town')),
			new Field('age',  array('value' => 1)),
			new OneToMany('friends', array(
				'mapper_suffix' => 'User',
				// 'model_suffix'  => 'Magic',
			)),
		);
	}

}