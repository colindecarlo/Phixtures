<?php

namespace Phixtures;

class Connection
{

	protected $_connection = null;
	protected $_dsn = '';
	protected $_user = '';
	protected $_password = '';
	protected $_targetSchema = '';

	public function __construct($dsn = '', $user = '', $password = '', $targetSchema = '')
	{
		$this->setDsn($dsn);
		$this->_user = $user;
		$this->_password = $password;
		$this->_targetSchema = $targetSchema;

		if (!empty($this->_dsn) && !empty($user) && !empty($password)) {
			$this->connect();
		}

	}

	public function setDsn($dsn) {

		if ($this->_connection instanceof \PDO) {
			// drop the current connection
			$this->_connection = null;
		}

		$this->_setDsn($dsn);

		return $this;
	}

	protected function _setDsn($dsn)
	{
		$this->_dsn = $dsn;
	}

	public function connect()
	{
		$this->_connection = new \PDO($this->_dsn, $this->_user, $this->_password);

		return $this;
	}

	public function setUser($user)
	{
		$this->_user = $user;

		return $this;
	}

	public function setPassword($password)
	{
		$this->_password = $password;

		return $this;
	}

	public function setTargetSchema($schema)
	{
		$this->_targetSchema = $schema;

		return $this;
	}

	public function getConnection()
	{
		return $this->_connection;
	}

	public function getTargetSchema()
	{
		return $this->_targetSchema;
	}
}
