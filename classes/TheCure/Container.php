<?php
/**
 * A dependency injection container
 *
 * If you are following the conventions of The Cure you will
 * likely only ever initialise this object in your
 * application. Use this object to get your Mapper instances.
 * 
 * @example
 * 
 *     $container = new MapperContainer('Mongo');
 *     $container->mapper('User'); // => Mapper_Mongo_User
 *
 * @package     TheCure
 * @category    Container
 * @copyright   Gigniute, 2012
 */
namespace TheCure;

use TheCure\IdentityMap;
use TheCure\Factory;

use TheCure\Mapper\ConnectionSetGet;
use TheCure\Mapper\ContainerSetGet;
use TheCure\Mapper\FactorySetGet;
use TheCure\Mapper\IdentitiesSetGet;
use TheCure\Mapper\ConfigSetGet;

class Container {

	protected $config;

	protected $factory;

	protected $connection;

	protected $identities;

	protected $mappers;

	protected $type;
	
	/**
	 * Create a new instance of MapperContainer by providing
	 * a base class.
	 *
	 *     // Mappers will be prefixed with Mapper_Mongo_
	 *     new Container('Mongo') 
	 *
	 *     // Mappers will be prefixed with Mapper_Diff_One_
	 *     new Container('Diff_One') 
	 * 
	 * @param string $type a base type of mapper
	 */
	public function __construct($type)
	{
		$this->type = $type;
	}

	/**
	 * @return string
	 */
	protected function type()
	{
		return $this->type;
	}

	/**
	 * @return mixed
	 */
	protected function factory()
	{
		if ($this->factory === NULL)
		{
			$this->factory = new Factory($this->config('factory'));
		}

		return $this->factory;
	}

	/**
	 * @param  null $config
	 * @return mixed
	 */
	public function config($config = NULL)
	{
		if (is_array($config))
		{
			$this->config = $config;
			return;
		}

		if ($this->config === NULL)
		{
			if (class_exists('Kohana') AND isset(\Kohana::$config))
			{
				$this->config = \Kohana::$config->load('thecure')->as_array();
			}
			else
			{
				$this->config = require __DIR__.'/../../config/thecure.php';
			}
		}

		if (isset($this->config[$config]))
		{
			return $this->config[$config];
		}

		return $this->config;
	}

	/**
	 * @return mixed
	 */
	protected function mapper_config()
	{
		$mappers = $this->config('mappers');
		$key = $this->type();

		if (isset($mappers[$key]))
		{
			return $mappers[$key];
		}
	}

	/**
	 * @return mixed
	 */
	protected function connection()
	{
		if ($this->connection === NULL)
		{
			$mapper_config = $this->mapper_config();

			if (isset($mapper_config['connection_class']))
			{
				$class = $mapper_config['connection_class'];
			}
			else
			{
				$class = $this->factory()->connection($this->type());
			}

			$this->connection = new $class($mapper_config);
		}

		return $this->connection;
	}

	/**
	 * Get IdentityMap.
	 * 
	 * @return  IdentityMap
	 */
	public function identities()
	{
		if ($this->identities === NULL)
		{
			$this->identities = new IdentityMap;
		}

		return $this->identities;
	}

	/**
	 * Get a mapper instance. Once a mapper of a certain type
	 * has been instantiated that object will continue to be
	 * returned.
	 *
	 *     $container = new Container('Mongo');
	 *     $container->mapper('User'); // => Mapper_Mongo_User
	 *
	 * @param   string  the class
	 * @return  Mapper
	 */
	public function mapper($suffix)
	{
		$class = $this->factory()->mapper($this->type(), $suffix);

		if ( ! isset($this->mappers[$class]))
		{
			$mapper = new $class;

			if ($mapper instanceOf ContainerSetGet)
			{
				$mapper->container($this);
			}

			if ($mapper instanceOf ConnectionSetGet)
			{
				$mapper->connection($this->connection());
			}
			
			if ($mapper instanceOf IdentitiesSetGet)
			{
				$mapper->identities($this->identities());
			}
			
			if ($mapper instanceOf ConfigSetGet)
			{
				$mapper->config($this->mapper_config());
			}

			if ($mapper instanceOf FactorySetGet)
			{
				$mapper->factory($this->factory());
			}

			$this->mappers[$class] = $mapper;
		}

		return $this->mappers[$class];
	}

}