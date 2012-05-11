<?php
namespace Gignite\TheCure\Models\Forum;

use Gignite\TheCure\Attributes;
use Gignite\TheCure\Field;
use Gignite\TheCure\Relationships\HasMany;
use Gignite\TheCure\Models\Magic as MagicModel;

class Thread extends MagicModel {
	
	public static function attributes()
	{
		return new Attributes(
			new Field('title'),
			new Field('message'),
			new HasMany('posts', array(
				'mapper_suffix' => 'Forum\Post',
			)));
	}

}