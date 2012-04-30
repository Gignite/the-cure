<?php
/**
 * A class name factory
 * 
 *     $factory->connection('Mongo');
 *     $factory->mapper('Mongo', 'User');
 *     $factory->domain($mapper);
 *     $factory->model($mapper, 'Admin');
 *
 * @package     TheCure
 * @category    Factory
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure;

use Gignite\TheCure\Mappers\Mapper;

class Factory {

	protected $config;
	
	/**
	 * Create new name factory.
	 *
	 * @param   array  config
	 * @return  void
	 */
	public function __construct(array $config)
	{
		$this->config = $config;
	}

	protected function config()
	{
		return $this->config;
	}

	protected function prefix($type)
	{
		return $this->config['prefixes'][$type];
	}

	protected function separator()
	{
		return $this->config['separator'];
	}
	
	/**
	 * Get mapper class.
	 *
	 * @param   string  mapper type
	 * @param   string  mapper suffix
	 * @return  string  class name
	 */
	public function mapper($type, $suffix)
	{
		$class = $this->prefix('mapper');
		$class .= $this->separator();
		$class .= $type;
		$class .= $this->separator();
		$class .= $suffix;
		return $class;
	}
	
	/**
	 * Get domain from Mapper.
	 *
	 * @param   Mapper
	 * @return  string  domain
	 */
	public function domain(Mapper $mapper)
	{
		$config = $this->config();
		$class = get_class($mapper);
		$domain = str_replace($this->prefix('mapper'), '', $class);
		$domainPos = strrpos($domain, $this->separator()) + 1;
		$domain = substr($domain, $domainPos);
		return $domain;
	}
	
	/**
	 * Get model class from Mapper and model suffix.
	 *
	 * @param   Mapper
	 * @param   string  model suffix
	 * @return  string  model class
	 */
	public function model(Mapper $mapper, $suffix = NULL)
	{
		$class = $this->prefix('model');
		$class .= $this->separator();
		$class .= $this->domain($mapper);

		if ($suffix !== NULL)
		{
			$class .= $this->separator();
			$class .= $suffix;
		}

		return $class;
	}

}