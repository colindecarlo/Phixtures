<?php

namespace Phixtures\Test\Reflect;

use Phixtures\Test\TestCase;

use Phixtures\Reflect\Schema;
use Phixtures\Reflect\Table;
use Phixtures\Reflect\DatabaseAdapter\Base;

class SchemaTest extends TestCase
{
	public function test_constructor()
	{
		$adapter = $this->_getAdapterMock();
		$schema = new Schema('phixtures', $adapter);

		$this->assertEquals('phixtures', \PHPUnit_Framework_Assert::readAttribute($schema, '_name'));
		$this->assertSame($adapter, \PHPUnit_Framework_Assert::readAttribute($schema, '_adapter'));
	}

	public function test_reflect()
	{
		$adapter = $this->_getAdapterMock();
		$this->_expectTables($adapter);

		$schema = new Schema('phixtures', $adapter);
		$expectedTables = array(
			'table1' => new Table('table1', $schema),
			'table2' => new Table('table2', $schema),
		);

		$schema->reflect();

		$this->assertEquals($expectedTables, \PHPUnit_Framework_Assert::readAttribute($schema, '_tables'));
	}

	public function test_getters()
	{
		$adapter = $this->_getAdapterMock();
		$schema = new Schema('phixtures', $adapter);

		$this->assertEquals('phixtures', $schema->getName());
		$this->assertSame($adapter, $schema->getAdapter());
		$this->assertEquals(array(), $schema->getTables());
	}

	public function test_can_iterate_and_count()
	{
		$adapter = $this->_getAdapterMock();
		$schema = new Schema('phixtures', $adapter);
		$expectedTables = array(
			'table1' => new Table('table1', $schema),
			'table2' => new Table('table2', $schema),
		);

		$reflectedProperty = new \ReflectionProperty('Phixtures\Reflect\Schema', '_tables');
		$reflectedProperty->setAccessible(true);
		$reflectedProperty->setValue($schema, $expectedTables);

		$this->assertCount(2, $schema);
		foreach ($schema as $tableName => $table) {
			$this->assertSame($expectedTables[$tableName], $table);
		}
	}

	protected function _expectTables(Base $adapter)
	{

		$adapter->expects($this->once())
			->method('getSchemaTables')
			->with($this->isInstanceOf('Phixtures\Reflect\Schema'))
			->will($this->returnValue(array('table1', 'table2')));
	}


	

}
