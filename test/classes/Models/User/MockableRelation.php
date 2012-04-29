<?php
namespace Models\User;

use Gignite\TheCure\Models\Magic as MagicModel;
use Gignite\TheCure\Mapper\Container;

class MockableRelation extends MagicModel {

	public static $relation;

	public static function fields()
	{
		return parent::fields() + array(
			'relation' => call_user_func(static::$relation),
		);
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