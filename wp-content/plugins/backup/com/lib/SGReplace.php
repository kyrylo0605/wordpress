<?php

class SGReplace
{
	protected $search;
	protected $replace;
	protected $delegate;

	private $state = null;
	private $reloadStartTs = null;
	private $tableCursor = 0;
	private $columnCursor = 0;
	private $isSecondryTable = false;
	private $inprogress = false;

	/**
	 * Count of rows to be replaced at a time
	 *
	 * @var int
	 */
	protected $page_size = 100;

	public function __construct($delegate)
	{
		$this->delegate = $delegate;
	}

	public function replaceValuesInQuery($oldValue, $newValue, $query)
	{
		if ($oldValue == $newValue) {
			return $query;
		}

		$query = str_replace($oldValue, $newValue, $query);

		return $query;
	}

	/**
	 * The main loop triggered in step 5. Up here to keep it out of the way of the
	 * HTML. This walks every table in the db that was selected in step 3 and then
	 * walks every row and column replacing all occurrences of a string with another.
	 * We split large tables into  blocks (size is set via $page_size)when dealing with them to save
	 * on memory consumption.
	 *
	 * @param string $search  What we want to replace
	 * @param string $replace What we want to replace it with.
	 * @param string $tables  The name of the table we want to look at.
	 *
	 * @return array    Collection of information gathered during the run.
	 */

	public function run_search_replace( $search, $replace, $tables )
	{
		$this->reloadStartTs = time();
		$this->state = $this->delegate->getState();

		if ($search === $replace) {
			return;
		}

		$this->tableCursor = $this->state->getTableCursor();
		$this->inprogress  = $this->state->getInprogress();

		if ($this->inprogress) {
			$this->columnCursor = $this->state->getColumnCursor();
		}
		else {
			$this->columnCursor = 0;
		}

		SGPing::update();
		for ($i = $this->tableCursor; $i < count($tables); $i++) {

			$this->isSecondryTable = true;
			if (preg_match('/'.SG_ENV_DB_PREFIX.'+\d+_/', $tables[$i])) {
				$this->isSecondryTable = false;
			}

			$this->replace_values($search, $replace, $tables[$i]);

			SGPing::update();
			$this->tableCursor++;
			$this->columnCursor = 0;
			$this->inprogress = false;

			if ($this->shouldReload()) {
				$i += 1;
				SGPing::update();
				$this->delegate->saveStateData(false, $i, 0);
				$this->delegate->reload();
			}
		}
	}

	public function shouldReload()
	{
		$currentTime = time();

		if (($currentTime - $this->reloadStartTs) >= SG_RELOAD_TIMEOUT) {
			return true;
		}

		return false;
	}

	public function replace_values( $search = '', $replace = '', $table )
	{
		SGPing::update();

		// check we have a search string, bail if not
		if ( empty( $search ) ) {
			return;
		}
		//split columns array in primary key string and columns array

		$columns     = $this->delegate->get_columns( $table );

		$primary_key = $columns[ 0 ];
		$columns     = $columns[ 1 ];

		if ( NULL === $primary_key ) {
			return;
		}

		// Count the number of rows we have in the table if large we'll split into blocks, This is a mod from Simon Wheatley
		$row_count = $this->delegate->get_rows( $table );

		$page_size = $this->page_size;
		$pages     = ceil( $row_count / $page_size );

		SGPing::update();
		for ( $page = $this->columnCursor; $page < $pages; $page ++ ) {

			$start = $page * $page_size;


			// Grab the content of the table
			$data = $this->delegate->get_table_content( $table, $start, $page_size );

			if ( ! $data ) {
				continue;
			}

			SGPing::update();
			foreach ( $data as $row ) {

				$update_sql = array();
				$where_sql  = array();
				$update     = FALSE;
				SGPing::update();
				foreach ( $columns as $column ) {

					$data_to_fix = $row[ $column ];

					if ( $column === $primary_key ) {
						$where_sql[] = $column . ' = "' . $this->mysql_escape_mimic( $data_to_fix ) . '"';
						continue;
					}
					/*
						// exclude cols
						if ( in_array( $column, $this->exclude_cols ) )
							continue;

						// include cols
						if ( ! empty( $this->include_cols ) && ! in_array( $column, $this->include_cols ) )
							continue;
					*/

					// Run a search replace on the data that'll respect the serialisation.
					$edited_data = $this->recursive_unserialize_replace( $search, $replace, $data_to_fix );

					// Something was changed
					if ( $edited_data !== $data_to_fix ) {

						$update_sql[] = $column . ' = "' . $this->mysql_escape_mimic( $edited_data ) . '"';
						$update       = TRUE;

					}
				}

				// Determine what to do with updates.
				if ( $update && ! empty( $where_sql ) ) {
					// If there are changes to make, run the query.

					$result = $this->delegate->update( $table, $update_sql, $where_sql );
				}
			}

			SGPing::update();
			if($this->shouldReload()) {
				$page += 1;
				SGPing::update();
				$this->delegate->flush();
				$this->delegate->saveStateData(true, $this->tableCursor, $page);
				$this->delegate->reload();
			}
		}

		$this->delegate->flush();
	}

	/**
	 * Take a serialised array and unserialize it replacing elements as needed and
	 * unserializing any subordinate arrays and performing the replace on those too.
	 *
	 * @param string              $from       String we're looking to replace.
	 * @param string              $to         What we want it to be replaced with
	 * @param array|string|object $data       Used to pass any subordinate arrays back to in.
	 * @param bool                $serialised Does the array passed via $data need serialising.
	 *
	 * @return array The original array with all elements replaced as needed.
	 */
	public function recursive_unserialize_replace( $from = '', $to = '', $data = '', $serialised = FALSE )
	{

		// some unserialized data cannot be re-serialised eg. SimpleXMLElements
		try {

			if ( is_string( $data ) && ( $unserialized = @unserialize( $data ) ) !== FALSE ) {
				$data = $this->recursive_unserialize_replace( $from, $to, $unserialized, TRUE );
			} elseif ( is_array( $data ) ) {
				$_tmp = array();
				foreach ( $data as $key => $value ) {
					$_tmp[ $key ] = $this->recursive_unserialize_replace( $from, $to, $value, FALSE );
				}

				$data = $_tmp;
				unset( $_tmp );
			} // Submitted by Tina Matter
			elseif ( is_object( $data ) ) {
				// $data_class = get_class( $data );
				$_tmp  = $data; // new $data_class( );
				$props = get_object_vars( $data );
				foreach ( $props as $key => $value ) {
					$_tmp->$key = $this->recursive_unserialize_replace( $from, $to, $value, FALSE );
				}

				$data = $_tmp;
				unset( $_tmp );
			} else {
				if ( is_string( $data ) ) {

					$from = backupGuardParseUrl($from);

					$patternAddition = "";
					if (SG_SUBDOMAIN_INSTALL && !$this->isSecondryTable) {
						$migrateTo = backupGuardParseUrl($to);
						$patternAddition = ".";
					}
					else {
						$migrateTo = $to;
					}

					$pattern = "/(?<![^>=".$patternAddition."'\"\s\/\|])(((https|http):\/\/)(((w+w+w)|(w+\d+w)|(w+w+\d)|(\d+w+w))+\.)|((https|http)?:\/\/)|(((w+w+w)|(w+\d+w)|(w+w+\d)|(\d+w+w))+\.)|(\/\/)|(\/\/)(((w+w+w)|(w+\d+w)|(w+w+\d)|(\d+w+w))+\.))?(".preg_quote($from, '/').")(?![^'\"\s\/])/i";
					$data = preg_replace($pattern, $migrateTo, $data);
				}
			}

			if ( $serialised ) {
				return serialize( $data );
			}

		}
		catch ( Exception $error ) {

			$this->add_error( $error->getMessage(), 'results' );

		}

		return $data;
	}

	/**
	 * Checks if the submitted string is a JSON object
	 *
	 * @param            $string
	 * @param bool|FALSE $strict
	 *
	 * @return bool
	 */

	protected function is_json( $string, $strict = FALSE )
	{

		$json = @json_decode( $string, TRUE );
		if ( $strict === TRUE && ! is_array( $json ) ) {
			return FALSE;
		}

		return ! ( $json === NULL || $json === FALSE );
	}

	/**
	 * Mimics the mysql_real_escape_string function. Adapted from a post by 'feedr' on php.net.
	 *
	 * @link   http://php.net/manual/en/function.mysql-real-escape-string.php#101248
	 * @access public
	 *
	 * @param  array|string $input The string to escape.
	 *
	 * @return string
	 */

	public function mysql_escape_mimic( $input )
	{

		if ( is_array( $input ) ) {
			return array_map( __METHOD__, $input );
		}
		if ( ! empty( $input ) && is_string( $input ) ) {
			return str_replace(
				array( '\\', "\0", "\n", "\r", "'", '"', "\x1a" ),
				array( '\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z' ),
				$input
			);
		}

		return $input;
	}
}
