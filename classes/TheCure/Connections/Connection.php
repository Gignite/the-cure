<?php
/**
 * A connection interface
 *
 * @package     TheCure
 * @category    Connections
 * @copyright   Gignite, 2012
 */
namespace TheCure\Connections;

interface Connection {

	/**
	 * @abstract
	 * @return mixed
	 */
	public function get();

}