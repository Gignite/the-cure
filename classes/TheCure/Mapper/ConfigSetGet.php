<?php
/**
 * An interface for a mapper that uses a config
 *
 * @package     TheCure
 * @category    Mapper
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure\Mapper;

interface ConfigSetGet {
	
	/**
	 * Get/set the factory.
	 *
	 * @param   array  if setting
	 * @return  array  if getting
	 */
	public function config($config);

}