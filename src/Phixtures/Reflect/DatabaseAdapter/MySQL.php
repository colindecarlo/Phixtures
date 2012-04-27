<?php

namespace Phixtures\Reflect\DatabaseAdapter;

use Phixtures\Reflect\DatabaseAdapter\Base;
use Phixtures\Reflect\Schema;
use Phixtures\Reflect\Table;

class MySQL extends Base
{
	public function getSchemaTables(Schema $schema)
	{
		$query = 'SELECT TABLE_NAME
			      FROM TABLES
				  WHERE TABLE_SCHEMA = :schema_name';

		$params = array(':schema_name' => $schema->getName());
		$result = $this->_getQueryResult($query, $params);

		$tables = array();
		foreach ($result as $table) {
			$tables[] = $table['TABLE_NAME'];
		}

		return $tables;

	}

	public function getTableColumns(Table $table)
	{
		$query = 'SELECT COLUMN_NAME,
			             ORDINAL_POSITION,
			             COLUMN_DEFAULT,
			             IS_NULLABLE,
			             DATA_TYPE,
			             CHARACTER_MAXIMUM_LENGTH,
			             NUMERIC_PRECISION,
			             NUMERIC_SCALE,
			             COLUMN_TYPE
			      FROM COLUMNS
			      WHERE TABLE_NAME = :table_name
			      AND TABLE_SCHEMA = :schema_name';

		$params = array(
			':table_name' => $table->getName(),
			':schema_name' => $table->getSchema()->getName()
		);
		$result = $this->_getQueryResult($query, $params);

		foreach ($result as $column) {
			$columns[$column['COLUMN_NAME']] = array(
				'nullable' => $column['IS_NULLABLE'] === 'YES',
				'default' => $column['COLUMN_DEFAULT'] != 'NULL' ? $column['COLUMN_DEFAULT'] : null,
			);
		}

		return $columns;
	}

	protected function _getQueryResult($query, $parameters)
	{
		$sth = $this->getHandle()->prepare($query);
		$sth->execute($parameters);

		return $sth->fetchAll(\PDO::FETCH_ASSOC);
	}
}


