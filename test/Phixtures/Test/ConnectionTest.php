<?php

namespace Phixtures\Test;

use Phixtures\Test\TestCase;

use Phixtures\Connection;

class ConnectionTest extends TestCase
{
	public function test_construct_no_params()
	{
		$conn = new Connection();
		$this->assertInstanceOf('Phixtures\Connection', $conn);
		$this->assertEquals('', \PHPUnit_Framework_Assert::readAttribute($conn, '_dsn'));
		$this->assertEquals('', \PHPUnit_Framework_Assert::readAttribute($conn, '_user'));
		$this->assertEquals('', \PHPUnit_Framework_Assert::readAttribute($conn, '_password'));
		$this->assertNull(\PHPUnit_Framework_Assert::readAttribute($conn, '_connection'));
	}

	public function test_construct_with_dsn()
	{
		$dsn = 'sqlite::memory:';
		$conn = new Connection($dsn);
		$this->assertInstanceOf('Phixtures\Connection', $conn);
		$this->assertEquals($dsn, \PHPUnit_Framework_Assert::readAttribute($conn, '_dsn'));
		$this->assertEquals('', \PHPUnit_Framework_Assert::readAttribute($conn, '_user'));
		$this->assertEquals('', \PHPUnit_Framework_Assert::readAttribute($conn, '_password'));
		$this->assertNull(\PHPUnit_Framework_Assert::readAttribute($conn, '_connection'));
	}

	public function test_construct_with_all_the_params()
	{
		$dsn = 'sqlite::memory:';
		$user = 'root';
		$password = 'localdev';
		$conn = new Connection($dsn, $user, $password);
		$this->assertInstanceOf('Phixtures\Connection', $conn);
		$this->assertEquals($dsn, \PHPUnit_Framework_Assert::readAttribute($conn, '_dsn'));
		$this->assertEquals($user, \PHPUnit_Framework_Assert::readAttribute($conn, '_user'));
		$this->assertEquals($password, \PHPUnit_Framework_Assert::readAttribute($conn, '_password'));
		$this->assertNull(\PHPUnit_Framework_Assert::readAttribute($conn, '_connection'));

	}

	public function test_setDsn_no_connection()
	{
		$conn = new Connection('sqlite::memory:');
		$this->assertNull(\PHPUnit_Framework_Assert::readAttribute($conn, '_connection'));
		$conn->setDsn('mysql:dbname=phixtures;host=127.0.0.1');
		$this->assertNull(\PHPUnit_Framework_Assert::readAttribute($conn, '_connection'));
	}

	public function test_setDsn_with_connection()
	{
		$dsn = 'sqlite::memory:';
		$conn = new Connection($dsn);

		$pdo = new \PDO($dsn);
		$this->_setProtectedProperty($conn, '_connection', $pdo);

		$this->assertInstanceOf('PDO', \PHPUnit_Framework_Assert::readAttribute($conn, '_connection'));
		$conn->setDsn('sqlite::memory:');
		$this->assertNull(\PHPUnit_Framework_Assert::readAttribute($conn, '_connection'));
	}

	public function test_connect_connects()
	{
		$conn = new Connection('sqlite::memory:');
		$conn->connect();

		$this->assertInstanceOf('PDO', \PHPUnit_Framework_Assert::readAttribute($conn, '_connection'));
	}

	public function test_getConnection_connects_if_it_has_to()
	{
		$conn = new Connection('sqlite::memory:');
		$connection = $conn->getConnection();

		$this->assertInstanceOf('PDO', \PHPUnit_Framework_Assert::readAttribute($conn, '_connection'));
	}

	public function test_fluent_interface()
	{
		$conn = new Connection();
		$conn->setDsn('sqlite::memory:')
			->setUser('root')
			->setPassword('localdev')
			->connect();

		$this->assertEquals('sqlite::memory:', \PHPUnit_Framework_Assert::readAttribute($conn, '_dsn'));
		$this->assertEquals('root', \PHPUnit_Framework_Assert::readAttribute($conn, '_user'));
		$this->assertEquals('localdev', \PHPUnit_Framework_Assert::readAttribute($conn, '_password'));
		$this->assertInstanceOf('PDO', \PHPUnit_Framework_Assert::readAttribute($conn, '_connection'));
	}
		
	public function test_getters()
	{
		$dsn = 'sqlite::memory:';
		$conn = new Connection($dsn);

		$pdo = new \PDO($dsn);
		$this->_setProtectedProperty($conn, '_connection', $pdo);

		$this->assertSame(\PHPUnit_Framework_Assert::readAttribute($conn, '_connection'), $conn->getConnection());
	}
}
