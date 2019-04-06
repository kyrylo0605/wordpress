<?php

require_once(SG_LIB_PATH.'SGReplace.php');

class SGMigrate
{
	private $sgReplace = null;
	private $dbAdapter = null;
	private $token = null;
	private $delegate = null;

	public function __construct($dbAdapter=null)
	{
		$this->dbAdapter = $dbAdapter;
		$this->sgReplace = new SGReplace($this);

		$this->token = backupGuardGenerateToken();
	}

	public function setDelegate($delegate)
	{
		$this->delegate = $delegate;
	}

	public function get_rows( $table )
	{
		$table = $this->dbAdapter->escapeSql($table);

		$count = 0;
		$tableRowsNum = $this->dbAdapter->query('SELECT COUNT(*) AS total FROM '.$table);
		$count = @$tableRowsNum[0]['total'];
		return $count;
	}

	public function get_columns( $table )
	{
		$primary_key = NULL;
		$columns = array();
		$fields = $this->dbAdapter->query( 'DESCRIBE ' . $table, array(), OBJECT);

		if ( is_array( $fields ) ) {
			foreach ( $fields as $column ) {
				$columns[] = $column->Field;
				if ( 'PRI' === $column->Key ) {
					$primary_key = $column->Field;
				}
			}
		}

		return array( $primary_key, $columns );
	}

	public function get_table_content( $table, $start, $end )
	{
		$data = $this->dbAdapter->query( "SELECT * FROM $table LIMIT $start, $end");

		return $data;
	}

	public function update( $table, $update_sql, $where_sql )
	{
		$sql = 'UPDATE ' . $table . ' SET ' . implode( ', ', $update_sql ) .
			   ' WHERE ' . implode( ' AND ', array_filter( $where_sql ) );

		return $this->dbAdapter->exec( $sql );
	}

	public function flush()
	{
		$this->dbAdapter->flush();
	}

	public function migrateMultisite($newDomain, $newPath, $oldPath, $tables)
	{
		$this->migrateMainMultisite($newDomain, $newPath, $tables);
		$this->migrateSecondaryMultisites($newDomain, $newPath, $oldPath, $tables);
	}

	private function migrateMainMultisite($newDomain, $newPath, $tables)
	{
		foreach($tables as $table) {
			$primaryKey = 'id';
			if ($table == SG_ENV_DB_PREFIX.'blogs') {
				$primaryKey = 'blog_id';
			}
			$sql = 'UPDATE '.$table.' SET domain=%s, path=%s WHERE '.$primaryKey.'=1';

			$this->dbAdapter->query($sql, array(
				$newDomain,
				$newPath
			));
		}
	}

	private function migrateSecondaryMultisites($newDomain, $newPath, $oldPath, $tables)
	{
		foreach($tables as $table) {
			$primaryKey = 'id';
			if ($table == SG_ENV_DB_PREFIX.'blogs') {
				$primaryKey = 'blog_id';
			}
			$sql = "SELECT * FROM ".$table." WHERE $primaryKey<>1";
			$data = $this->dbAdapter->query($sql);

			if (count($data)) {
				foreach ($data as $row) {
					$sql = 'UPDATE '.$table.' SET domain=%s, path=%s WHERE '.$primaryKey.'=%d';

					if (SG_SUBDOMAIN_INSTALL) {
						$domain = str_replace(SGConfig::get('SG_MULTISITE_OLD_DOMAIN'), $newDomain, $row['domain']);
					}
					else {
						$domain = $newDomain;
					}

					$path = '';
					if (strpos($row['path'], $oldPath, 0) === 0) {
						$path = substr($row['path'], strlen($oldPath));
						$path = $newPath.$path;

						$this->dbAdapter->query($sql, array(
							$domain,
							$path,
							$row[$primaryKey]
						));
					}
				}
			}
		}
	}

	public function getState()
	{
		return $this->delegate->getState();
	}

	public function reload()
	{
		$this->delegate->reload();
	}

	public function saveStateData($inprogress, $tableCursor, $columnCursor)
	{
		$state = $this->getState();

		$state->setAction(SG_STATE_ACTION_MIGRATING_DATABASE);
		$state->setInprogress($inprogress);
		$state->setTableCursor($tableCursor);
		$state->setColumnCursor($columnCursor);
		$state->setToken($this->token);
		$state->save();
	}

	public function migrate($oldValue, $newValue, $tables)
	{
		return $this->sgReplace->run_search_replace($oldValue, $newValue, $tables);
	}

	public function replaceValuesInQuery($oldValue, $newValue, $query)
	{
		return $this->sgReplace->replaceValuesInQuery($oldValue, $newValue, $query);
	}

	public function migrateDBPrefix()
	{
		if (SG_ENV_ADAPTER == SG_ENV_WORDPRESS) { // Logic for wordpress db prefix migration
			$oldDbPrefix = SGConfig::get('SG_OLD_DB_PREFIX');
			$dgMiscMigratebleTables = explode(',', SG_MISC_MIGRATABLE_TABLES);

			if ($oldDbPrefix == SG_ENV_DB_PREFIX) {
				return;
			}

			foreach ($dgMiscMigratebleTables as $dgMiscMigratebleTable) {
				if ($dgMiscMigratebleTable == SG_ENV_DB_PREFIX.'options') {
					$params = array(
						SG_ENV_DB_PREFIX.SG_WP_OPTIONS_MIGRATABLE_VALUES,
						$oldDbPrefix.SG_WP_OPTIONS_MIGRATABLE_VALUES
					);

					$res = $this->dbAdapter->query('UPDATE '.$dgMiscMigratebleTable.' SET option_name=%s WHERE option_name=%s', $params);
				}
				else {
					$sgUserMetaMigratableValues = explode(',', SG_WP_USERMETA_MIGRATABLE_VALUES);
					foreach ($sgUserMetaMigratableValues as $sgUserMetaMigratableValue) {
						$params = array(
							SG_ENV_DB_PREFIX.$sgUserMetaMigratableValue,
							$oldDbPrefix.$sgUserMetaMigratableValue
						);

						$res = $this->dbAdapter->query('UPDATE '.$dgMiscMigratebleTable.' SET meta_key=%s WHERE meta_key=%s', $params);
					}
				}
			}
		}
		else {
			return;
		}
	}
}
