<?php
namespace Gignite\TheCure\Relation;

use Gignite\TheCure\Mapper\Container;

interface Add {
	
	public function add(Container $container, $object, $relation);

}