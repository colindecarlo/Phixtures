<?php

namespace Phixtures\Reflect;

use Phixtures\Reflect\DatabaseAdapter\DatabaseAdapterInterface;
use Phixtures\Reflect\Schema;

class Table
{

	protected $_name;
	protected $_schema;
	protected $_adapter;
	protected $_columns = array();
	protected $_baseClass = '';

	public function __construct($name, Schema $schema)
	{
		$this->_name = $name;
		$this->_schema = $schema;
		$this->_adapter = $this->_schema->getAdapter();
	}

	public function getName()
	{
		return $this->_name;
	}

	public function getSchema()
	{
		return $this->_schema;
	}

	public function reflect()
	{
		$this->_columns = $this->_adapter->getTableColumns($this);
	}

	public function buildBaseClass()
	{
		if (empty($this->_columns)) {
			$this->reflect();
		}

		$loader = new \Twig_Loader_FileSystem(__DIR__ . '/../../Templates');
		$twig = new \Twig_Environment($loader);

		$template = $twig->loadTemplate('BaseClass.php.twig');
		$this->_baseClass = $template->render(array(
			'name' => $this->_name,
			'columns' => $this->_columns
		));

		return $this;
	}

}
