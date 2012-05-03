<?php
namespace Gignite\TheCure\Models\Forum;

use Gignite\TheCure\Field;
use Gignite\TheCure\Relationships\BelongsToOne;
// use Gignite\TheCure\Relationships\ManyToMany;
use Gignite\TheCure\Models\Magic as MagicModel;

class Post extends MagicModel {
	
	public static function attributes()
	{
		return array(
			new Field('message'),
			new BelongsToOne('thread', array(
				'mapper_suffix' => 'Forum\Thread',
				'foreign'       => 'posts',
			)),
			// new ManyToMany('tags'),
		);
	}

}