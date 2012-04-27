<?php

namespace Phixtures;

class DIContainer extends \Pimple
{

	protected $_config = array(
		'BOSS_MODE' => TRUE, // this is the only place shouty bools are allowed
		'database' => array(
			'dsn' => 'mysql:dbname=information_schema;host=127.0.0.1',
			'user' => 'root',
			'password' => 'localdev',
			'target_schema' => 'phixtures',
			'adapter_class' => '\Phixtures\Reflect\DatabaseAdapter\MySQL',
		),
		'fixtures' => array(
			'target_directory' => '/Users/colin/development/scratch/phixtures'
		),
	);

	public function __construct()
	{
		foreach ($this->_config as $key => $value) {
			$this[$key] = $value;
		}

		$methods = get_class_methods($this);

		foreach ($methods as $method) {
			if (0 === strpos($method, '_build')) {
				$this->$method();
			}
		}
	}

	protected function _buildConnection()
	{
		$this['database.connection'] = $this->share(
			function () {
				$config = $this['database'];
				return new \Phixtures\Connection($config['dsn'], $config['user'], $config['password']);
			}
		);
	}

	protected function _buildAdapter()
	{
		$this['database.adapter'] = $this->share(
			function () {
				$config = $this['database'];
				return new $config['adapter_class']($this['database.connection']);
			}
		);
	}

	protected function _buildSchema()
	{
		$this['database.schema'] = $this->share(
			function () {
				$config = $this['database'];
				return new \Phixtures\Reflect\Schema($config['target_schema'], $this['database.adapter']);
			}
		);
	}
}
