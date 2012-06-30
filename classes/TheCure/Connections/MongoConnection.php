<?php
/**
 * A MongoDB connection
 *
 * @package     TheCure
 * @category    Connection
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Connections;

class MongoConnection implements Connection {

	/**
	 * @var \Mongo
	 */
	protected $connection;

	/**
	 * Setup a new MongoDB connection.
	 *
	 * @param array $config
	 */
	public function __construct(array $config)
	{
		$this->config = $config;
	}

	/**
	 * Returns the config key if it exists otherwise
	 * returns the default value.
	 *
	 * @param  string $key
	 * @param  null   $default
	 * @return string|null
	 */
	protected function config($key, $default = NULL)
	{
		if (isset($this->config[$key]))
		{
			return $this->config[$key];
		}

		return $default;
	}

	/**
	 * Creates a new MongoDB connection using the native PHP driver.
	 *
	 * @return \Mongo
	 */
	protected function connect()
	{
		return new \Mongo($this->config('connection', 'mongodb://127.0.0.1'));
	}

	/**
	 * Connects to MongoDB if it hasn't already and selects the DB
	 * specified in the configuration.
	 *
	 * @return \MongoDB
	 */
	public function get()
	{
		if ($this->connection === NULL)
		{
			$this->connection = $this->connect();
		}

		return $this->connection->selectDb($this->config('db'));
	}

}