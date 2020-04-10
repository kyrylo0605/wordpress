<?php
namespace Bookly\Backend\Modules\Debug\Lib;

/**
 * Class Schema
 * @package Bookly\Backend\Modules\Debug\Lib
 */
class Schema
{
    /**
     * Get table constraints
     *
     * @param string $table
     * @return array
     */
    public function getTableConstraints( $table )
    {
        /** @global \wpdb $wpdb */
        global $wpdb;

        $tableConstraints = array();
        $records = $wpdb->get_results(
            'SELECT COLUMN_NAME
                  , CONSTRAINT_NAME
                  , REFERENCED_COLUMN_NAME
                  , REFERENCED_TABLE_NAME
               FROM information_schema.KEY_COLUMN_USAGE
              WHERE TABLE_NAME = "' . $table . '"
                AND REFERENCED_TABLE_NAME IS NOT NULL
                AND CONSTRAINT_SCHEMA = SCHEMA()
                AND CONSTRAINT_NAME <> "PRIMARY";'
        );
        if ( $records ) {
            foreach ( $records as $row ) {
                $constraint = array(
                    'column_name'            => $row->COLUMN_NAME,
                    'referenced_table_name'  => $row->REFERENCED_TABLE_NAME,
                    'referenced_column_name' => $row->REFERENCED_COLUMN_NAME,
                    'constraint_name'        => $row->CONSTRAINT_NAME,
                    'reference_exists'       => $this->existsColumn( $row->REFERENCED_TABLE_NAME, $row->REFERENCED_COLUMN_NAME ),
                );
                $key = $row->COLUMN_NAME . $row->REFERENCED_TABLE_NAME . $row->REFERENCED_COLUMN_NAME;
                $tableConstraints[ $key ] = $constraint;
            }
        }

        return $tableConstraints;
    }

    /**
     * Check exists table
     *
     * @param string $table
     * @return bool
     */
    public function existsTable( $table )
    {
        global $wpdb;

        return (bool) $wpdb->query( $wpdb->prepare(
            'SELECT 1 FROM `information_schema`.`tables` WHERE TABLE_NAME = %s AND TABLE_SCHEMA = SCHEMA() LIMIT 1',
            $table
        ) );
    }

    /**
     * Get table structure
     *
     * @param string $table
     * @return array
     */
    public function getTableStructure( $table )
    {
        global $wpdb;

        $tableStructure = array();
        $results = $wpdb->get_results( $wpdb->prepare( 'SELECT COLUMN_NAME, 
            CASE 
                WHEN DATA_TYPE IN( \'smallint\', \'int\', \'bigint\' ) THEN CONCAT( DATA_TYPE, IF(COLUMN_TYPE LIKE \'%unsigned\', \' unsigned\', \'\'))
                ELSE COLUMN_TYPE
            END AS DATA_TYPE, 
            IS_NULLABLE, COLUMN_KEY, COLUMN_DEFAULT, EXTRA
         FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = %s', $table ), ARRAY_A );
        if ( $results ) {
            foreach ( $results as $row ) {
                $tableStructure[ $row['COLUMN_NAME'] ] = array(
                    'type'       => $row['DATA_TYPE'],
                    'is_nullabe' => $row['IS_NULLABLE'] === 'YES' ? 1 : 0,
                    'extra'      => $row['EXTRA'],
                    'default'    => $row['COLUMN_DEFAULT'],
                    'key'        => $row['COLUMN_KEY']
                );
            }
        }

        return $tableStructure;
    }

    /**
     * Check exists column in table
     *
     * @param string $table
     * @param string $column_name
     * @return bool
     */
    protected function existsColumn( $table, $column_name )
    {
        global $wpdb;

        return (bool) $wpdb->query( $wpdb->prepare( 'SELECT 1 FROM information_schema.COLUMNS
            WHERE TABLE_NAME = %s AND COLUMN_NAME = %s AND TABLE_SCHEMA = SCHEMA() LIMIT 1',
            $table,
            $column_name
        ) );
    }
}