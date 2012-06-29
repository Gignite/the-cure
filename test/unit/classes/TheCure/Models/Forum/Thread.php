<?php
namespace TheCure\Models\Forum;

use TheCure\Attributes;
use TheCure\Field;
use TheCure\Relationships\HasMany;
use TheCure\Models\Magic as MagicModel;

class Thread extends MagicModel {
	
	public static function attributes()
	{
		return new Attributes(
			new Field('title'),
			new Field('message'),
			new HasMany('posts', array(
				'mapperSuffix' => 'Forum\Post',
			)));
	}

}