<?php

namespace Phixtures\Reflect;

use Phixtures\Reflect\DatabaseAdapter\DatabaseAdapterInterface;

class Table {

	protected $_adapter = null;
	protected $_name = null;
	protected $_columns = array();
	protected $_baseClass = '';

	public function __construct($name, DatabaseAdapterInterface $adapter)
	{
		$this->_name = $name;
		$this->_adapter = $adapter;
	}

	public function reflect()
	{
		$this->_columns = $this->_adapter->getTableColumns($this->_name);
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
