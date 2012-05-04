<?php

namespace Phixtures\Reflect\DatabaseAdapter;

use Phixtures\Connection;
use Phixtures\Reflect\Table;
use Phixtures\Reflect\Schema;

abstract class Base
{

	protected $_connection;

	public function __construct(Connection $connection)
	{
		$this->_connection = $connection;
	}

	public function getHandle()
	{
		return $this->_connection->getConnection();
	}

	abstract public function getTableColumns(Table $table);
	abstract public function getSchemaTables(Schema $schema);
}
