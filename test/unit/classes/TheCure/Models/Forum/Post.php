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
				'mapper' => 'Forum\Thread',
				'foreign'       => 'posts',
			)),
			new HasManyRelationship('tags', array(
				'mapper' => 'Forum\Tag',
				// 'via' => array(
				// 	'mapper' => 'Forum\Post',
				// ),
			)));
	}

}