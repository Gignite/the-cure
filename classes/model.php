<?php

abstract class Model {

	protected $object;
	
	public function __object($object = NULL)
	{
		if ($object === NULL)
		{
			if ($this->object === NULL)
			{
				$this->object = new StdClass;
			}
			
			return $this->object;
		}

		$this->object = $object;
	}

}