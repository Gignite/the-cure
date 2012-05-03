<?php
namespace Gignite\TheCure\Models\Forum;

use Gignite\TheCure\Field;
use Gignite\TheCure\Relationships\OneToMany;
use Gignite\TheCure\Models\Magic as MagicModel;

class Thread extends MagicModel {
	
	public static function attributes()
	{
		return array(
			new Field('title'),
			new Field('message'),
			new OneToMany('posts', array(
				'mapper_suffix' => 'Forum\Post',
			)),
		);
	}

}