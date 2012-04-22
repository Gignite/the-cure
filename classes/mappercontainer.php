<?php
/**
 * A dependency injection container
 *
 * If you are following the conventions of The Cure you will
 * likely only ever initialise this object in your
 * application. Use this object to get your Mapper instances.
 * 
 *     $container = new MapperContainer('Mongo');
 *     $container->mapper('User'); // => Mapper_Mongo_User
 *
 * @package     TheCure
 * @category    Container
 * @copyright   Gignite, 2011
 */
class MapperContainer {

	protected $connection;

	protected $identities;

	protected $mappers;
	
	protected $type;
	
	/**
	 * Create a new instance of MapperContainer by providing
	 * a base class.
	 *
	 *     // Mappers will be prefixed with Mapper_Mongo_
	 *     new MapperContainer('Mongo') 
	 *
	 *     // Mappers will be prefixed with Mapper_Diff_One_
	 *     new MapperContainer('Diff_One') 
	 * 
	 * @param   string  a base type of mapper
	 * @return  void
	 */
	public function __construct($type)
	{
		$this->type = $type;
	}

	protected function type()
	{
		return $this->type;
	}

	protected function config()
	{
		static $config;

		if ($config === NULL)
		{
			$config = Kohana::$config->load("mappers.{$this->type()}");
		}

		return $config;
	}

	protected function connection()
	{
		if ($this->connection === NULL)
		{
			$connection_class = "Connection_{$this->type()}";
			$this->connection = new $connection_class($this->config());
		}

		return $this->connection;
	}

	protected function identities()
	{
		if ($this->identities === NULL)
		{
			$this->identities = new IdentityMap;
		}

		return $this->identities;
	}

	protected function mapper_class($mapper)
	{
		return "Mapper_{$this->type()}_{$mapper}";
	}

	/**
	 * Get a mapper instance. Once a mapper of a certain type
	 * has been instantiated that object will continue to be
	 * returned.
	 *
	 *     $container = new MapperContainer('Mongo');
	 *     $container->mapper('User'); // => Mapper_Mongo_User
	 *
	 * @param   string  the class
	 * @return  Mapper
	 */
	public function mapper($class)
	{
		$class = $this->mapper_class($class);

		if ( ! isset($this->mappers[$class]))
		{
			$mapper = new $class;

			if ($mapper instanceOf MapperConnection)
			{
				$mapper->connection($this->connection());
			}
			
			$mapper->identities($this->identities());
			$mapper->config($this->config());

			$this->mappers[$class] = $mapper;
		}

		return $this->mappers[$class];
	}

}