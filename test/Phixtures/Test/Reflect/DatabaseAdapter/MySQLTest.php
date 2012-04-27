<?php

namespace Phixtures\Test\Reflect\DatabaseAdapter;

use Phixtures\Test\TestCase;

use Phixtures\Reflect\DatabaseAdapter\MySQL;
use Phixtures\Connection;
use Phixtures\Reflect\Schema;
use Phixtures\Reflect\Table;

class MySQLTest extends TestCase
{
	public function setUp()
	{
		$conn = new Connection('mysql:dbname=information_schema;host=127.0.0.1', 'root', 'localdev');
		$this->adapter = new MySQL($conn);
		$this->schema = new Schema('phixtures', $this->adapter);
		$this->table1 = new Table('table1', $this->schema);
	}

	public function test_getSchemaTables()
	{
		$tables = $this->adapter->getSchemaTables($this->schema);
		$expectedTables = array('table1', 'table2');
		$this->assertEquals($expectedTables, $tables);
	}

	public function test_getTableColumns()
	{
		$columns = $this->adapter->getTableColumns($this->table1);
		$expectedColumns = array(
			'column_1' => array('nullable' => false, 'default' => "42"),
			'column_2' => array('nullable' => true, 'default' => null),
		);

		$this->assertEquals($expectedColumns, $columns);
	}

}
