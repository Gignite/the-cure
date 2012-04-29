<?php
/**
 * Describe an iterable collection of objects
 * 
 * In order to iterate a result set from [Mapper_Mongo] we use
 * an [Object_Iterable_Collection].
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Collection
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
namespace Gignite\TheCure\Collections;

class Iterable extends Collection {

	public function current()
	{
		return $this->collection()->current();
	}

	public function key()
	{
		return $this->collection()->key();
	}

	public function next()
	{
		$this->collection()->next();
	}

	public function valid()
	{
		return $this->collection()->valid();
	}

	public function rewind()
	{
		$this->collection()->rewind();
	}

	public function count()
	{
		return $this->collection()->count();
	}

}