<?php
namespace TheCure\Models\Forum;

use TheCure\Attributes;
use TheCure\Field;
use TheCure\Relationships\BelongsToMany;
use TheCure\Models\Magic as MagicModel;

class Tag extends MagicModel {
	
	public static function attributes()
	{
		return new Attributes(
			new Field('name'),
			new BelongsToMany('posts', array(
				'mapperSuffix' => 'Forum\Post',
				'foreign'       => 'tags',
			// )),
			// new HasMany('posts', array(
			// 	'mapperSuffix' => 'Forum\Post',
			// 	'via' => new HasMany('tags', array(
			// 		'mapperSuffix' => 'Forum\Tag',
			// 	)),
			)));
		
	}

}