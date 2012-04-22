<?php

abstract class Relationship extends Field {

	protected $name;

	protected $model;

	protected $mapper;

	public function __construct($name, $mapper, $model = NULL)
	{
		$this->name   = $name;
		$this->mapper = $mapper;
		$this->model  = $model;
	}

	public function name()
	{
		return $this->name;
	}

	protected function model()
	{
		return $this->model;
	}

	protected function mapper()
	{
		return $this->mapper;
	}

	abstract public function find(MapperContainer $container, $value);

}