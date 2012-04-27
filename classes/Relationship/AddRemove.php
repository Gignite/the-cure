<?php

interface Relationship_AddRemove {
	
	public function add(MapperContainer $container, $object, $relation);
	public function remove(MapperContainer $container, $object, $relation);

}