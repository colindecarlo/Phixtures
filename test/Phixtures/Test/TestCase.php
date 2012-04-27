<?php

namespace Phixtures\Test;

class TestCase extends \PHPUnit_Framework_TestCase
{
	protected function _getAdapterMock()
	{
		$adapter = $this->getMockBuilder('Phixtures\Reflect\DatabaseAdapter\Base')
			->disableOriginalConstructor()
			->getMockForAbstractClass();

		return $adapter;
	}
}
