<?php
namespace Gignite\TheCure\Models\Forum;

use Gignite\TheCure\Attributes;
use Gignite\TheCure\Field;
use Gignite\TheCure\Relationships\BelongsToOne;
use Gignite\TheCure\Relationships\HasMany;
use Gignite\TheCure\Models\Magic as MagicModel;

class Post extends MagicModel {
	
	public static function attributes()
	{
		return new Attributes(
			new Field('message'),
			new BelongsToOne('thread', array(
				'mapper_suffix' => 'Forum\Thread',
				'foreign'       => 'posts',
			)),
			new HasMany('tags', array(
				'mapper_suffix' => 'Forum\Tag',
				// 'via' => array(
				// 	'mapper_suffix' => 'Forum\Post',
				// ),
			)));
	}

}