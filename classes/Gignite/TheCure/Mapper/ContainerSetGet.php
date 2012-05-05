<?php
/**
 * An interface for a mapper that uses a container
 *
 * @package     TheCure
 * @category    Mapper
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure\Mapper;

use Gignite\TheCure\Container;

interface ContainerSetGet {
	
	/**
	 * @param   Container|null  $container
	 * @return  mixed
	 */
	public function container(Container $container = NULL);

}