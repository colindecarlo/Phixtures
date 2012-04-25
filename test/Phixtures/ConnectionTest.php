<?php

namespace Phixtures\Test;

use Phixtures\Connection;

class ConnectionTest extends \PHPUnit_Framework_TestCase
{
	public function test_construct_no_params()
	{
		$conn = new Connection();
		$this->assertInstanceOf('Phixtures\Connection', $conn);
		$this->assertNull(\PHPUnit_Framework_Assert::readAttribute($conn, '_connection'));
	}

	public function test_construct_with_dsn()
	{
		$conn = new Connection('mysql:dbname=information_schema;host=127.0.0.1');
		$this->assertInstanceOf('Phixtures\Connection', $conn);
		$this->assertNull(\PHPUnit_Framework_Assert::readAttribute($conn, '_connection'));
	}

	public function test_construct_with_all_the_params()
	{
		$conn = new Connection('mysql:dbname=information_schema;host=127.0.0.1', 'root', 'localdev', 'phixtures');
		$this->assertInstanceOf('Phixtures\Connection', $conn);
		$this->assertInstanceOf('PDO', \PHPUnit_Framework_Assert::readAttribute($conn, '_connection'));

	}

	public function test_setDsn_no_connection()
	{
		$conn = new Connection('mysql:dbname=information_schema;host=127.0.0.1');
		$this->assertNull(\PHPUnit_Framework_Assert::readAttribute($conn, '_connection'));
		$conn->setDsn('mysql:dbname=phixtures;host=127.0.0.1');
		$this->assertNull(\PHPUnit_Framework_Assert::readAttribute($conn, '_connection'));
	}

	public function test_setDsn_with_connection()
	{
		$conn = new Connection('mysql:dbname=information_schema;host=127.0.0.1', 'root', 'localdev');
		$this->assertInstanceOf('PDO', \PHPUnit_Framework_Assert::readAttribute($conn, '_connection'));
		$conn->setDsn('mysql:dbname=phixtures;host=127.0.0.1');
		$this->assertNull(\PHPUnit_Framework_Assert::readAttribute($conn, '_connection'));
	}

	public function test_fluent_interface()
	{
		$conn = new Connection();
		$conn->setDsn('mysql:dbname=information_schema;host=127.0.0.1')
			->setUser('root')
			->setPassword('localdev')
			->setTargetSchema('phixtures')
			->connect();

		$this->assertEquals('mysql:dbname=information_schema;host=127.0.0.1', \PHPUnit_Framework_Assert::readAttribute($conn, '_dsn'));
		$this->assertEquals('root', \PHPUnit_Framework_Assert::readAttribute($conn, '_user'));
		$this->assertEquals('localdev', \PHPUnit_Framework_Assert::readAttribute($conn, '_password'));
		$this->assertEquals('phixtures', \PHPUnit_Framework_Assert::readAttribute($conn, '_targetSchema'));
		$this->assertInstanceOf('PDO', \PHPUnit_Framework_Assert::readAttribute($conn, '_connection'));
	}
		
	public function test_getters()
	{
		$conn = new Connection('mysql:dbname=information_schema;host=127.0.0.1', 'root', 'localdev', 'phixtures');
		$this->assertSame(\PHPUnit_Framework_Assert::readAttribute($conn, '_connection'), $conn->getConnection());
		$this->assertSame(\PHPUnit_Framework_Assert::readAttribute($conn, '_targetSchema'), $conn->getTargetSchema());
	}
}
