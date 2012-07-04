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
 *     $container = new Container('Mongo');
 *     $container->mapper('User'); // => Mappers\Mongo\User
 *
 * @package     TheCure
 * @category    Container
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure;

use TheCure\Maps\IdentityMap;

use TheCure\Factories\Factory;

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
	 * Get Factory. If a factory isn't initialised one will.
	 *
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
	 * Get/set config.
	 *
	 * This method tries to load a config using
	 * `Kohana::$config` and if that fails, it loads a default
	 * config using `require`.
	 *
	 * If setting a config before ::config() is used to get a
	 * config then no config will be loaded and only the
	 * provided config will be used.
	 * 
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
				$this->config = \Kohana::$config->load('the-cure')->as_array();
			}
			else
			{
				$this->config = require __DIR__.'/../../config/the-cure.php';
			}
		}

		if (isset($this->config[$config]))
		{
			return $this->config[$config];
		}

		return $this->config;
	}

	/**
	 * Get the mapper specific configuration.
	 * 
	 * @return mixed
	 */
	protected function mapperConfig()
	{
		$mappers = $this->config('mappers');
		$key = $this->type();

		if (isset($mappers[$key]))
		{
			return $mappers[$key];
		}
	}

	/**
	 * Get connection class name from config or Factory then
	 * initialise an instance.
	 * 
	 * @return mixed
	 */
	public function connection()
	{
		if ($this->connection === NULL)
		{
			$mapperConfig = $this->mapperConfig();

			if (isset($mapperConfig['connectionClass']))
			{
				$class = $mapperConfig['connectionClass'];
			}
			else
			{
				$class = $this->factory()->connection($this->type());
			}

			$this->connection = new $class($mapperConfig);
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
	 *     $container->mapper('User'); // => Mappers\Mongo\User
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
				$mapper->config($this->mapperConfig());
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