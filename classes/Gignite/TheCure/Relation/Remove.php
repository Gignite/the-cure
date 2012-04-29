<?php
namespace Gignite\TheCure\Relation;

use Gignite\TheCure\Mapper\Container;

interface Remove {
	
	public function remove(Container $container, $object, $relation);

}