<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


if ( ! class_exists( 'AWS_Helpers' ) ) :

    /**
     * Class for plugin help methods
     */
    class AWS_Helpers {

        /*
         * Removes scripts, styles, html tags
         */
        static public function html2txt( $str ) {
            $search = array(
                '@<script[^>]*?>.*?</script>@si',
                '@<[\/\!]*?[^<>]*?>@si',
                '@<style[^>]*?>.*?</style>@siU',
                '@<![\s\S]*?--[ \t\n\r]*>@'
            );
            $str = preg_replace( $search, '', $str );

            $str = esc_attr( $str );
            $str = stripslashes( $str );
            $str = str_replace( array( "\r", "\n" ), ' ', $str );

            $str = str_replace( array(
                "Â·",
                "â€¦",
                "â‚¬",
                "&shy;"
            ), "", $str );

            return $str;
        }

        /*
         * Get amount of indexed products
         */
        static public function get_indexed_products_count() {

            global $wpdb;

            $table_name = $wpdb->prefix . AWS_INDEX_TABLE_NAME;

            $indexed_products = 0;

            if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) === $table_name ) {

                $sql = "SELECT COUNT(*) FROM {$table_name} GROUP BY ID;";

                $indexed_products = $wpdb->query( $sql );

            }

            return $indexed_products;

        }
        
        /*
         * Get special characters that must be striped
         */
        static public function get_special_chars() {
            
            $chars = array(
                '-',
                '_',
                '|',
                '+',
                '`',
                '~',
                '!',
                '@',
                '#',
                '$',
                '%',
                '^',
                '&',
                '*',
                '(',
                ')',
                '\\',
                '?',
                ';',
                ':',
                "'",
                '"',
                ".",
                ",",
                "<",
                ">",
                "{",
                "}",
                "/",
                "[",
                "]",
            );
            
            return apply_filters( 'aws_special_chars', $chars );
            
        }

        /*
         * Replace stopwords
         */
        static public function filter_stopwords( $str_array ) {

            $stopwords = AWS()->get_settings( 'stopwords' );

            if ( $stopwords && $str_array && ! empty( $str_array ) ) {
                $stopwords_array = explode( ',', $stopwords );
                if ( $stopwords_array && ! empty( $stopwords_array ) ) {

                    $stopwords_array = array_map( 'trim', $stopwords_array );

                    foreach ( $str_array as $str_word => $str_count ) {
                        if ( in_array( $str_word, $stopwords_array ) ) {
                            unset( $str_array[$str_word] );
                        }
                    }

                }
            }

            return $str_array;

        }

        /*
         * Strip shortcodes
         */
        static public function strip_shortcodes( $str ) {
            $str = preg_replace( '#\[[^\]]+\]#', '', $str );
            return $str;
        }

    }

endif;