<?php

interface MapperActions {
	
	abstract public function find_one($suffix, $id = NULL);

	abstract public function save(Model $model);

	abstract public function delete($model);

}