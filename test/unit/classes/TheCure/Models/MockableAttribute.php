<?php
namespace TheCure\Models;

use TheCure\Lists\AttributeList;
use TheCure\Models\MagicModel;
use TheCure\Container;

class MockableAttribute extends MagicModel {

	public static $attribute;

	public static function attributes()
	{
		return new AttributeList(call_user_func(static::$attribute));
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