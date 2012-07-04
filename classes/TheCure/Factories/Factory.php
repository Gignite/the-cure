<?php
/**
 * A class name factory
 *
 * This object is used for constructing class names based on
 * configuration and method arguments. Every public method
 * returns a string of a class name.
 * 
 * @example
 *
 *     // These examples assume a default configuration
 * 
 *     $factory->connection('Mongo'); // => 'Connections\Mongo'
 *     $factory->mapper('Mongo', 'User'); // => 'Mappers\Mongo\User'
 *
 *     $mapper = new Mappers\Mongo\User;
 *     $factory->domain($mapper); // => 'User'
 *     $factory->model($mapper, 'Admin'); // => 'Models\User\Admin'
 *
 * @package     TheCure
 * @category    Factory
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Factories;

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
	 * @param  $type
	 * @return mixed
	 */
	protected function suffix($type)
	{
		return $this->config['suffixes'][$type];
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
	public function connection($connection)
	{
		$class = $this->prefix('connection');
		$class .= $connection;
		$class .= $this->suffix('connection');
		return $class;
	}
	
	/**
	 * Get mapper class.
	 *
	 * @param   string  mapper type
	 * @param   string  mapper suffix
	 * @return  string  class name
	 */
	public function mapper($type, $mapper)
	{
		$class = $this->prefix('mapper');
		$class .= $type;
		$class .= $this->separator();
		$class .= $mapper;
		$class .= $this->suffix('mapper');
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
		$domain = substr($domain, $domainPos, - strlen($this->suffix('mapper')));
		return $domain;
	}

	/**
	 * Get model class from Mapper and model suffix.
	 *
	 * @param  Mappers\Mapper $mapper
	 * @param  null $suffix
	 * @return string
	 */
	public function model(Mapper $mapper, $model = NULL)
	{
		$class = $this->prefix('model');
		$class .= $this->domain($mapper);

		if ($model !== NULL)
		{
			$class .= $model;
		}

		$class .= $this->suffix('model');
		return $class;
	}

}