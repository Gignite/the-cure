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
class Collection implements Iterator, Countable {

	protected $i;

	protected $collection;

	public function __construct($collection)
	{
		$this->collection = $collection;
		$this->rewind();
	}

	public function collection()
	{
		return $this->collection;
	}

	public function as_array()
	{
		return iterator_to_array($this);
	}

	public function current()
	{
		return $this->collection[$this->i];
	}

	public function key()
	{
		return $this->i;
	}

	public function next()
	{
		next($this->collection);
		$this->i = key($this->collection);
	}

	public function valid()
	{
		return isset($this->collection[$this->i]);
	}

	public function rewind()
	{
		reset($this->collection);
		$this->i = key($this->collection);
	}

	public function count()
	{
		return count($this->collection);
	}

}