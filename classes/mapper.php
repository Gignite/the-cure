<?php

interface Mapper {
	
	public function find_one($suffix, $id = NULL);

	public function save($model);

}