<?php

namespace Phixtures\Test\Reflect;

use Phixtures\Reflect\Table;
use Phixtures\Reflect\DatabaseAdapter\MySQL;
use Phixtures\Connection;

class TableTest extends \PHPUnit_Framework_TestCase
{
	public function test_buildBaseClass()
	{
		$conn = new Connection('mysql:dbname=information_schema;host=127.0.0.1', 'root', 'localdev', 'phixtures');
		$adapter = new MySQL($conn);
		$table = new Table('table1', $adapter);
		$table->buildBaseClass();

		$this->assertStringEqualsFile(__DIR__ . '/../../fixtures/Table1BaseClass.php', \PHPUnit_Framework_Assert::readAttribute($table, '_baseClass'));

	}
}
