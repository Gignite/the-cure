<?php

interface MapperActions {
	
	public function find($suffix = NULL, array $where = NULL);

	public function find_one($suffix, $id = NULL);

	public function save(Model $model);

	public function delete($model);

}