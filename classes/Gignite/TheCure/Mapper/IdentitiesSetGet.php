<?php
/**
 * An interface for a mapper that uses a connection
 *
 * @package     TheCure
 * @category    Mapper
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure\Mapper;

use Gignite\TheCure\IdentityMap;

interface IdentitiesSetGet {
	
	/**
	 * Get/set the factory.
	 *
	 * @param   IdentityMap  if setting
	 * @return  IdentityMap  if getting
	 */
	public function identities(IdentityMap $identities = NULL);

}