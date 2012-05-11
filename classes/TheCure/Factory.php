<?php
/**
 * A class name factory
 * 
 * @example
 * 
 *     $factory->connection('Mongo');
 *     $factory->mapper('Mongo', 'User');
 *     $factory->domain($mapper);
 *     $factory->model($mapper, 'Admin');
 *
 * @package     TheCure
 * @category    Factory
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure;

use TheCure\Mappers\Mapper;

class Factory {

	protected $config;

	/**
	 * Create new name factory.
	 *
	 * @param array $config
	 */
	public function __construct(array $config)
	{
		$this->config = $config;
	}

	/**
	 * @return array
	 */
	protected function config()
	{
		return $this->config;
	}

	/**
	 * @param  $type
	 * @return mixed
	 */
	protected function prefix($type)
	{
		return $this->config['prefixes'][$type];
	}

	/**
	 * @return string
	 */
	protected function separator()
	{
		return $this->config['separator'];
	}

	/**
	 * @param  string $suffix
	 * @return string
	 */
	public function connection($suffix)
	{
		$class = $this->prefix('connection');
		$class .= $this->separator();
		$class .= $suffix;
		return $class;
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
	 * @param  Mappers\Mapper $mapper
	 * @return mixed|string
	 */
	public function domain(Mapper $mapper)
	{
		$config = $this->config();
		$class = get_class($mapper);
		$domain = str_replace($this->prefix('mapper'), '', $class);
		$domain = trim($domain, $this->separator());
		$domainPos = strpos($domain, $this->separator()) + 1;
		$domain = substr($domain, $domainPos);
		return $domain;
	}

	/**
	 * Get model class from Mapper and model suffix.
	 *
	 * @param  Mappers\Mapper $mapper
	 * @param  null $suffix
	 * @return string
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