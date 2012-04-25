<?php

namespace Phixtures\Reflect\DatabaseAdapter;

use Phixtures\Reflect\DatabaseAdapter\Base;

class MySQL extends Base
{
	public function getTableColumns($table)
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

		$sth = $this->getHandle()->prepare($query);
		$sth->execute(array(
			':table_name' => $table,
			':schema_name' => $this->_connection->getTargetSchema()
		));

		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);

		foreach ($result as $column) {
			$columns[$column['COLUMN_NAME']] = array(
				'nullable' => $column['IS_NULLABLE'] === 'YES',
				'default' => $column['COLUMN_DEFAULT'] != 'NULL' ? $column['COLUMN_DEFAULT'] : null,
			);
		}

		return $columns;
	}
}


