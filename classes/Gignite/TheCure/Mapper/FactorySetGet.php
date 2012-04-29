<?php
/**
 * An interface for a mapper that uses a factory
 *
 * @package     TheCure
 * @category    Mapper
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure\Mapper;

use Gignite\TheCure\Factory;

interface FactorySetGet {
	
	/**
	 * Get/set the factory.
	 *
	 * @param   Factory  if setting
	 * @return  Factory  if getting
	 */
	public function factory(Factory $factory = NULL);

}