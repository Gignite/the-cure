<?php
namespace TheCure\Models;

use TheCure\Attributes;
use TheCure\Models\Magic as MagicModel;
use TheCure\Container;

class MockableAttribute extends MagicModel {

	public static $attribute;

	public static function attributes()
	{
		return new Attributes(call_user_func(static::$attribute));
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