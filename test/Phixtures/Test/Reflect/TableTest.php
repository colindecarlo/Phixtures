<?php

namespace Phixtures\Test\Reflect;

use Phixtures\Test\TestCase;

use Phixtures\Reflect\Table;
use Phixtures\Reflect\Schema;
use Phixtures\Reflect\DatabaseAdapter\Base;

class TableTest extends TestCase
{
	public function setUp()
	{
		$this->expectedTable1Columns = array(
			'column_1' => array('nullable' => false, 'default' => "42"),
			'column_2' => array('nullable' => true, 'default' => null),
		);
	}

	public function test_reflect()
	{
		$schema = $this->_getSchema();

		$table = new Table('table1', $schema);
		$table->reflect();

		$this->assertEquals($this->expectedTable1Columns, \PHPUnit_Framework_Assert::readAttribute($table, '_columns'));

	}

	public function test_buildBaseClass()
	{
		$schema = $this->_getSchema();

		$table = new Table('table1', $schema);
		$table->buildBaseClass();

		$this->assertStringEqualsFile(__DIR__ . '/../../../fixtures/Table1BaseClass.php', \PHPUnit_Framework_Assert::readAttribute($table, '_baseClass'));

	}

	protected function _getSchema()
	{
		$adapter = $this->_getAdapterMock();
		$this->_expectTable1Columns($adapter);

		return new Schema('Schema1', $adapter);
	}

	protected function _expectTable1Columns(Base $adapter)
	{
		$adapter->expects($this->once())
			->method('getTableColumns')
			->with($this->isInstanceOf('Phixtures\Reflect\Table'))
			->will($this->returnValue($this->expectedTable1Columns));
	}

}
