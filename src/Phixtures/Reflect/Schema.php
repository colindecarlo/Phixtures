<?php

namespace Phixtures\Reflect;

use Phixtures\Reflect\DatabaseAdapter\DatabaseAdapterInterface;
use Phixtures\Reflect\Table;

class Schema implements \IteratorAggregate, \Countable
{
	protected $_name;
	protected $_adapter;
	protected $_tables = array();

	public function __construct($name, DatabaseAdapterInterface $adapter)
	{
		$this->_name = $name;
		$this->_adapter = $adapter;
	}

	public function getName()
	{
		return $this->_name;
	}

	public function getAdapter()
	{
		return $this->_adapter;
	}

	public function getTables()
	{
		return $this->_tables;
	}

	public function getIterator()
	{
		return new \ArrayIterator($this->_tables);
	}

	public function count()
	{
		return count($this->_tables);
	}

	public function reflect()
	{
		$schemaTables = $this->_adapter->getSchemaTables($this);

		foreach ($schemaTables as $tableName) {
			$this->_tables[$tableName] = new Table($tableName, $this);
		}
	}
}
