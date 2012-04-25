<?php

namespace Phixtures\Test\Reflect\DatabaseAdapter;

use Phixtures\Reflect\DatabaseAdapter\MySQL;
use Phixtures\Connection;

class MySQLTest extends \PHPUnit_Framework_TestCase
{
	public function test_getTableColumns()
	{
		$conn = new Connection('mysql:dbname=information_schema;host=127.0.0.1', 'root', 'localdev', 'phixtures');
		$adapter = new MySQL($conn);
		$columns = $adapter->getTableColumns('table1');
		$expectedColumns = array(
			'column_1' => array('nullable' => false, 'default' => "42"),
			'column_2' => array('nullable' => true, 'default' => null),
		);

		$this->assertEquals($expectedColumns, $columns);
	}
}
