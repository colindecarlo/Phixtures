<?php

namespace Phixtures\Test;

use Phixtures\DIContainer;

use Phixtures\Test\TestCase;

class DIContainerTest extends TestCase
{

	public function setUp()
	{
		$this->container = new DIContainer();
	}

	public function test_constructor_sets_parameters()
	{
		$expectedBossModeConfig = true;
		$expectedDatabaseConfig = array(
			'dsn' => 'mysql:dbname=information_schema;host=127.0.0.1',
			'user' => 'root',
			'password' => 'localdev',
			'target_schema' => 'phixtures',
			'adapter_class' => '\Phixtures\Reflect\DatabaseAdapter\MySQL',
		);
		$expectedFixturesConfig = array(
			'target_directory' => '/Users/colin/development/scratch/phixtures'
		);

		$this->assertEquals($expectedBossModeConfig, $this->container['BOSS_MODE']);
		$this->assertEquals($expectedDatabaseConfig, $this->container['database']);
		$this->assertEquals($expectedFixturesConfig, $this->container['fixtures']);
	}

	public function test_constructor_calls_build_methods()
	{
		$expectedKeys = array(
			'database.connection',
			'database.adapter',
			'database.schema'
		);

		foreach ($expectedKeys as $key) {
			$this->assertTrue(isset($this->container[$key]));
		}
	}

	public function test_connection_is_built()
	{
		$connection = $this->container['database.connection'];
		$this->assertInstanceOf('\Phixtures\Connection', $connection);
	}

	public function test_adapter_is_built()
	{
		$adapter = $this->container['database.adapter'];
		$this->assertInstanceOf('\Phixtures\Reflect\DatabaseAdapter\DatabaseAdapterInterface', $adapter);
	}

	public function test_schema_is_built()
	{
		$schema = $this->container['database.schema'];
		$this->assertInstanceOf('\Phixtures\Reflect\Schema', $schema);
	}
}
