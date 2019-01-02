<?php
/**
 * AWS plugin integrations
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Integrations' ) ) :

    /**
     * Class for main plugin functions
     */
    class AWS_Integrations {

        private $data = array();

        /**
         * @var AWS_Integrations The single instance of the class
         */
        protected static $_instance = null;

        /**
         * Main AWS_Integrations Instance
         *
         * Ensures only one instance of AWS_Integrations is loaded or can be loaded.
         *
         * @static
         * @return AWS_Integrations - Main instance
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Constructor
         */
        public function __construct() {

            // Protected categories plugin
            if ( class_exists('WC_PPC_Util' ) ) {

                $hidden_categories = array();
                $show_protected	   = WC_PPC_Util::showing_protected_categories();

                // Get all the product categories, and check which are hidden.
                foreach ( WC_PPC_Util::to_category_visibilities( WC_PPC_Util::get_product_categories() ) as $category ) {
                    if ( $category->is_private() || ( ! $show_protected && $category->is_protected() ) ) {
                        $hidden_categories[] = $category->term_id;
                    }
                }

                if ( $hidden_categories && ! empty( $hidden_categories ) ) {

                    $this->data['exclude_categories'] = $hidden_categories;

                    $args = array(
                        'posts_per_page'      => -1,
                        'fields'              => 'ids',
                        'post_type'           => 'product',
                        'post_status'         => 'publish',
                        'ignore_sticky_posts' => true,
                        'suppress_filters'    => true,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field'    => 'id',
                                'terms'    => $hidden_categories
                            )
                        )
                    );

                    $exclude_products = get_posts( $args );

                    if ( $exclude_products && count( $exclude_products ) > 0 ) {

                        $this->data['exclude_products'] = $exclude_products;

                    }

                }

            }

            if ( isset( $this->data['exclude_categories'] ) ) {
                add_filter( 'aws_terms_exclude_product_cat', array( $this, 'filter_protected_cats_term_exclude' ) );
            }

            if ( isset( $this->data['exclude_products'] ) ) {
                add_filter( 'aws_exclude_products', array( $this, 'filter_products_exclude' ) );
            }

        }

        /*
         * Exclude product categories
         */
        public function filter_protected_cats_term_exclude( $exclude ) {
            foreach( $this->data['exclude_categories'] as $to_exclude ) {
                $exclude[] = $to_exclude;
            }
            return $exclude;
        }

        /*
         * Exclude products
         */
        public function filter_products_exclude( $exclude ) {
            foreach( $this->data['exclude_products'] as $to_exclude ) {
                $exclude[] = $to_exclude;
            }
            return $exclude;
        }

    }

endif;

add_action( 'init', 'AWS_Integrations::instance' );