<?php
namespace TheCure\Models\Forum;

use TheCure\Lists\AttributeList;

use TheCure\Attributes\Field;

use TheCure\Relationships\HasManyRelationship;

use TheCure\Models\MagicModel;

class Thread extends MagicModel {
	
	public static function attributes()
	{
		return new AttributeList(
			new Field('title'),
			new Field('message'),
			new HasManyRelationship('posts', array(
				'mapperSuffix' => 'Forum\Post',
			)));
	}

}