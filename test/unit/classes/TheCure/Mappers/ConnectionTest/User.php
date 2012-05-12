<?php
namespace TheCure\Mappers\ConnectionTest;

use TheCure\Mapper\ConnectionSetGet;
use TheCure\Connections\Connection;

class User implements ConnectionSetGet {

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