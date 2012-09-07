<?php
/**
 * Describe an iterable collection
 * 
 * In order to iterate a set of data we use a [Collection].
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Collection
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
namespace TheCure\Collections;

class Collection implements \Iterator, \Countable {

	protected $i;

	protected $collection;

	/**
	 * @param $collection
	 */
	public function __construct($collection)
	{
		if ($collection === NULL)
		{
			$collection = array();
		}
		
		$this->collection = $collection;
		$this->rewind();
	}

	/**
	 * @return mixed
	 */
	public function collection()
	{
		return $this->collection;
	}

	/**
	 * @return array
	 */
	public function asArray()
	{
		return iterator_to_array($this);
	}

	/**
	 * @return mixed
	 */
	public function current()
	{
		if ($this->valid())
		{
			return $this->collection[$this->i];
		}
	}

	/**
	 * @return string
	 */
	public function key()
	{
		return $this->i;
	}

	/**
	 * Move forwards one
	 */
	public function next()
	{
		next($this->collection);
		$this->i = key($this->collection);
	}

	/**
	 * @return bool
	 */
	public function valid()
	{
		return isset($this->collection[$this->i]);
	}

	/**
	 * Move backwards one
	 */
	public function rewind()
	{
		reset($this->collection);
		$this->i = key($this->collection);
	}

	/**
	 * @return int|void
	 */
	public function count()
	{
		return count($this->collection);
	}

}