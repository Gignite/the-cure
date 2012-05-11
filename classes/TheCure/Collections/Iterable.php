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
namespace TheCure\Collections;

class Iterable extends Collection {

	/**
	 * @return mixed
	 */
	public function current()
	{
		return $this->collection()->current();
	}

	/**
	 * @return mixed
	 */
	public function key()
	{
		return $this->collection()->key();
	}

	/**
	 * Move forwards one
	 */
	public function next()
	{
		$this->collection()->next();
	}

	/**
	 * @return bool
	 */
	public function valid()
	{
		return $this->collection()->valid();
	}

	/**
	 * Move backwards one
	 */
	public function rewind()
	{
		$this->collection()->rewind();
	}

	/**
	 * @return int|void
	 */
	public function count()
	{
		return $this->collection()->count();
	}

}