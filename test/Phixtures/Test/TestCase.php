<?php

namespace Phixtures\Test;

class TestCase extends \PHPUnit_Framework_TestCase
{
	protected function _setProtectedProperty($object, $property, $value)
	{
		$reflectedProperty = new \ReflectionProperty($object, $property);
		$reflectedProperty->setAccessible(true);
		$reflectedProperty->setValue($object, $value);
	}

	protected function _getAdapterMock()
	{
		$adapter = $this->getMockBuilder('Phixtures\Reflect\DatabaseAdapter\Base')
			->disableOriginalConstructor()
			->getMockForAbstractClass();

		return $adapter;
	}
}
