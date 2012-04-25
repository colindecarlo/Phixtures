<?php

namespace Phixtures\Reflect\DatabaseAdapter;

use Phixtures\Reflect\DatabaseAdapter\DatabaseAdapterInterface;
use Phixtures\Connection;

abstract class Base implements DatabaseAdapterInterface
{

	protected $_connection = null;

	public function __construct(Connection $connection)
	{
		$this->_connection = $connection;
	}

	public function getHandle()
	{
		return $this->_connection->getConnection();
	}

	abstract public function getTableColumns($table);
}
