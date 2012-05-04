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
		if (extension_loaded('pdo_mysql')) {
			$conn = new Connection($GLOBALS['MYSQL_DSN'], $GLOBALS['MYSQL_USER'], $GLOBALS['MYSQL_PASSWORD']);
			try {
				$conn->connect();
				$this->adapter = new MySQL($conn);
				$this->schema = new Schema($GLOBALS['MYSQL_DBNAME'], $this->adapter);
				$this->table1 = new Table('table1', $this->schema);
			} catch (\PDOException $e) {
				$this->markTestSkipped('Unable to connect to mysql, check your connection settings in phpunit.xml');
			}
		} else {
			$this->markTestSkipped('pdo_mysql extension is not loaded');
		}
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
