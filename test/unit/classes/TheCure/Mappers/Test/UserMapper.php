<?php
namespace TheCure\Mappers\Test;

use TheCure\Mapper\ConnectionSetGet;
use TheCure\Connections\Connection;

class UserMapper implements ConnectionSetGet {

	protected $connection;

	public function connection(Connection $connection = NULL)
	{
		if ($connection === NULL)
		{
			return $this->connection;
		}

		$this->connection = $connection;
	}

	public function identities() {}
	public function config() {}

}