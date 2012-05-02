<?php

namespace Phixtures;

use Symfony\Component\Console\Application as ConsoleApplication;

use Phixtures\DIContainer;
use Phixtures\Command\ReflectCommand;

class Application extends ConsoleApplication
{
	protected $_container;

	public function __construct(DIContainer $container)
	{
		$this->_container = $container; 
		parent::__construct('Phixtures', '0.1 (crash test dummy)');
	}

	protected function getDefaultCommands()
	{
		return array_merge(parent::getDefaultCommands(), array(
			new ReflectCommand($this->_container)
		));
	}
}
