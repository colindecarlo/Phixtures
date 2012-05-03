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
			'target_directory' => '/tmp/phixtures'
		),
	);

	public function __construct(array $config = array())
	{
		$this->_config = array_replace_recursive($this->_config, $config);

		foreach ($this->_config as $key => $value) {
			$this->_setConfigParameter($key, $value);
		}

		$methods = get_class_methods($this);

		foreach ($methods as $method) {
			if (0 === strpos($method, '_build')) {
				$this->$method();
			}
		}
	}

	protected function _setConfigParameter($key, $value) {
		if (is_array($value)) {
			foreach ($value as $subkey => $subvalue) {
				$this->_setConfigParameter($key . '.'  . $subkey, $subvalue);
			}
		} else {
			$this[$key] = $value;
		}
	}

	protected function _buildConnection()
	{
		$this['database.connection'] = $this->share(
			function ($c) {
				return new \Phixtures\Connection($c['database.dsn'], $c['database.user'], $c['database.password']);
			}
		);
	}

	protected function _buildAdapter()
	{
		$this['database.adapter'] = $this->share(
			function ($c) {
				return new $c['database.adapter_class']($c['database.connection']);
			}
		);
	}

	protected function _buildSchema()
	{
		$this['database.schema'] = $this->share(
			function ($c) {
				return new \Phixtures\Reflect\Schema($c['database.target_schema'], $c['database.adapter']);
			}
		);
	}

	protected function _buildApplication()
	{
		$this['application'] = $this->share(
			function ($c) {
				return new \Phixtures\Application($c);
			}
		);
	}
}
