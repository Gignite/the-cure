<?php
namespace Gignite\TheCure\Models\User;

use Gignite\TheCure\Models\Magic as MagicModel;
use Gignite\TheCure\Field;
use Gignite\TheCure\Relationships\OneToMany;

class Magic extends MagicModel {

	public static function fields()
	{
		return parent::fields() + array(
			'name'    => new Field('name', array('setter' => TRUE)),
			'age'     => new Field('age', array('value' => 1)),
			'friends' => new OneToMany('friends', array(
				'mapper_suffix' => 'User',
				'model_suffix'  => 'Magic',
			)),
		);
	}

}