<?php

namespace Phixtures\Reflect\DatabaseAdapter;

use Phixtures\Reflect\Table;
use Phixtures\Reflect\Schema;

interface DatabaseAdapterInterface
{
	public function getTableColumns(Table $table);
	public function getSchemaTables(Schema $schema);
	public function getHandle();
}
