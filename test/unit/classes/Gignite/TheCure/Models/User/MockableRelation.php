<?php
namespace Gignite\TheCure\Models\User;

use Gignite\TheCure\Attributes;
use Gignite\TheCure\Models\Magic as MagicModel;
use Gignite\TheCure\Container;

class MockableRelation extends MagicModel {

	public static $relation;

	public static function attributes()
	{
		return new Attributes(call_user_func(static::$relation));
	}

	public function __container(Container $container = NULL)
	{
		if ($this->__container === NULL)
		{
			parent::__container(new Container('Array'));
		}

		return parent::__container();
	}

}