<?php
/**
 * An interface for a mapper that uses a connection
 *
 * @package     TheCure
 * @category    Mapper
 * @copyright   Gignite, 2012
 */
namespace TheCure\Mapper;

use TheCure\Connections\Connection;

interface ConnectionSetGet {
	
	/**
	 * Get/set the connection.
	 *
	 * @param   Connection  $connection Connection if setting
	 * @return  Connection  If getting
	 */
	public function connection(Connection $connection = NULL);

}