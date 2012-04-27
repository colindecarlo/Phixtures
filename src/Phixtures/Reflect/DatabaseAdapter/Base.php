<?php

namespace Phixtures\Reflect\DatabaseAdapter;

use Phixtures\Connection;
use Phixtures\Reflect\Table;
use Phixtures\Reflect\Schema;
use Phixtures\Reflect\DatabaseAdapter\DatabaseAdapterInterface;

abstract class Base implements DatabaseAdapterInterface
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
