<?php
namespace Gignite\TheCure;

class Field {

	protected $name;

	public function __construct($name)
	{
		$this->name = $name;
	}

	public function name()
	{
		return $this->name;
	}

}