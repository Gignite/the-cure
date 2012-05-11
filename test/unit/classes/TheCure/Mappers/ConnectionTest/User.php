<?php
namespace Gignite\TheCure\Mappers\ConnectionTest;

use Gignite\TheCure\Mapper\ConnectionSetGet;
use Gignite\TheCure\Connections\Connection;

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