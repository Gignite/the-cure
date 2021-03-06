<?php
/**
 * An interface for a mapper that uses an IdentityMap
 *
 * @package     TheCure
 * @category    Mapper
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Mapper;

use TheCure\Maps\IdentityMap;

interface IdentitiesSetGet {
	
	/**
	 * Get/set the factory.
	 *
	 * @param   IdentityMap  $identities If setting
	 * @return  IdentityMap  If getting
	 */
	public function identities(IdentityMap $identities = NULL);

}