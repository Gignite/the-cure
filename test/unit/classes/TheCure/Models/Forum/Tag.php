<?php
namespace Gignite\TheCure\Models\Forum;

use Gignite\TheCure\Attributes;
use Gignite\TheCure\Field;
use Gignite\TheCure\Relationships\BelongsToMany;
use Gignite\TheCure\Models\Magic as MagicModel;

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