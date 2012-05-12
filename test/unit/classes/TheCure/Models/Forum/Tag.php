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
				'mapper_suffix' => 'Forum\Post',
				'foreign'       => 'tags',
			// )),
			// new HasMany('posts', array(
			// 	'mapper_suffix' => 'Forum\Post',
			// 	'via' => new HasMany('tags', array(
			// 		'mapper_suffix' => 'Forum\Tag',
			// 	)),
			)));
		
	}

}