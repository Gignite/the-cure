<?php
namespace Gignite\TheCure\Relation;

use Gignite\TheCure\Mapper\Container;

interface Set {
	
	public function set(Container $container, $object, $relation);

}