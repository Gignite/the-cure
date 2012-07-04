<?php
namespace TheCure\Models\Forum;

use TheCure\Lists\AttributeList;

use TheCure\Attributes\Field;

use TheCure\Relationships\BelongsToManyRelationship;

use TheCure\Models\MagicModel;

class Tag extends MagicModel {
	
	public static function attributes()
	{
		return new AttributeList(
			new Field('name'),
			new BelongsToManyRelationship('posts', array(
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