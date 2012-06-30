<?php
namespace TheCure\Mappers\TestConnection;

use TheCure\Mapper\ConnectionSetGet;
use TheCure\Connections\Connection;

class UserTestConnectionMapper implements ConnectionSetGet {

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