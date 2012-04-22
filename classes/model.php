<?php

abstract class Model {

	protected $__object;
	
	public function __object($object = NULL)
	{
		if ($object === NULL)
		{
			if ($this->__object === NULL)
			{
				$this->__object = new StdClass;
			}
			
			return $this->__object;
		}

		$this->__object = $object;
	}

}