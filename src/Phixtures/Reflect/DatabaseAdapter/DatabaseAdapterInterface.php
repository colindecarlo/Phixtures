<?php

namespace Phixtures\Reflect\DatabaseAdapter;

interface DatabaseAdapterInterface
{
	public function getTableColumns($table);
	public function getHandle();
}
