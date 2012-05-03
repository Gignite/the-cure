<?php
namespace Gignite\TheCure\Models\Forum;

use Gignite\TheCure\Field;
use Gignite\TheCure\Relationships\BelongsToMany;
use Gignite\TheCure\Models\Magic as MagicModel;

class Tag extends MagicModel {
	
	public static function attributes()
	{
		return array(
			new Field('name'),
			new BelongsToMany('posts', array(
				'mapper_suffix' => 'Forum\Post',
				'foreign'       => 'tags',
			)),
			// new HasMany('posts', array(
			// 	'mapper_suffix' => 'Forum\Post',
			// 	'via' => new HasMany('tags', array(
			// 		'mapper_suffix' => 'Forum\Tag',
			// 	)),
			// )),
		);
	}

}