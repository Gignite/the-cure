<?php
namespace TheCure\Models\Forum;

use TheCure\Lists\AttributeList;

use TheCure\Attributes\Field;

use TheCure\Relationships\BelongsToOneRelationship;
use TheCure\Relationships\HasManyRelationship;

use TheCure\Models\MagicModel;

class Post extends MagicModel {
	
	public static function attributes()
	{
		return new AttributeList(
			new Field('message'),
			new BelongsToOneRelationship('thread', array(
				'mapperSuffix' => 'Forum\Thread',
				'foreign'       => 'posts',
			)),
			new HasManyRelationship('tags', array(
				'mapperSuffix' => 'Forum\Tag',
				// 'via' => array(
				// 	'mapperSuffix' => 'Forum\Post',
				// ),
			)));
	}

}