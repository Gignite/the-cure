<?php
/**
 * An interface for a mapper that uses a connection
 *
 * @package     TheCure
 * @category    Mapper
 * @copyright   Gignite, 2011
 */
interface MapperConnection {
	
	/**
	 * Get/set the connection.
	 *
	 * @param   Connection  if setting
	 * @return  Connection  if getting
	 */
	public function connection(Connection $connection = NULL);

}