<?php
namespace TheCure\Models\Forum;

use TheCure\Attributes;
use TheCure\Field;
use TheCure\Relationships\BelongsToOne;
use TheCure\Relationships\HasMany;
use TheCure\Models\Magic as MagicModel;

class Post extends MagicModel {
	
	public static function attributes()
	{
		return new Attributes(
			new Field('message'),
			new BelongsToOne('thread', array(
				'mapperSuffix' => 'Forum\Thread',
				'foreign'       => 'posts',
			)),
			new HasMany('tags', array(
				'mapperSuffix' => 'Forum\Tag',
				// 'via' => array(
				// 	'mapperSuffix' => 'Forum\Post',
				// ),
			)));
	}

}