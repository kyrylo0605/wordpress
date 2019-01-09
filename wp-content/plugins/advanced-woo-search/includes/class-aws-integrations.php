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

            //add_action('woocommerce_product_query', array( $this, 'woocommerce_product_query' ) );

            if ( class_exists( 'BM' ) ) {
                add_action( 'aws_search_start', array( $this, 'b2b_set_filter' ) );
            }

            if ( class_exists( 'WC_PPC_Util' ) ) {
                add_action( 'aws_search_start', array( $this, 'wc_ppc_set_filter' ) );
            }

            if ( function_exists( 'dfrapi_currency_code_to_sign' ) ) {
                add_filter( 'woocommerce_currency_symbol', array( $this, 'dfrapi_set_currency_symbol_filter' ), 10, 2 );
            }

            add_filter( 'aws_terms_exclude_product_cat', array( $this, 'filter_protected_cats_term_exclude' ) );
            add_filter( 'aws_exclude_products', array( $this, 'filter_products_exclude' ) );

        }

        /*
         * B2B market plugin
         */
        public function b2b_set_filter() {

            if ( is_user_logged_in() ) {

                $user = wp_get_current_user();
                $role = ( array ) $user->roles;
                $user_role = $role[0];

                if ( $user_role ) {

                    $args = array(
                        'posts_per_page' => - 1,
                        'post_type'      => 'customer_groups',
                        'post_status'    => 'publish',
                    );

                    $posts           = get_posts( $args );
                    $customer_groups = array();

                    foreach ( $posts as $customer_group ) {
                        $customer_groups[$customer_group->post_name] = $customer_group->ID;
                    }

                    if ( isset( $customer_groups[$user_role] ) ) {
                        $curret_customer_group_id = $customer_groups[$user_role];

                        $whitelist = get_post_meta( $curret_customer_group_id, 'bm_conditional_all_products', true );

                        if ( $whitelist && $whitelist === 'off' ) {

                            $products_to_exclude = get_post_meta( $curret_customer_group_id, 'bm_conditional_products', false );
                            $cats_to_exclude = get_post_meta( $curret_customer_group_id, 'bm_conditional_categories', false );

                            if ( $products_to_exclude && ! empty( $products_to_exclude ) ) {

                                foreach( $products_to_exclude as $product_to_exclude ) {
                                    $this->data['exclude_products'][] = trim( $product_to_exclude, ',' );
                                }

                            }

                            if ( $cats_to_exclude && ! empty( $cats_to_exclude ) ) {

                                foreach( $cats_to_exclude as $cat_to_exclude ) {
                                    $this->data['exclude_categories'][] = trim( $cat_to_exclude, ',' );
                                }

                            }

                        }

                    }

                }

            }

        }

        /*
         * Protected categories plugin
         */
        public function wc_ppc_set_filter() {

            $hidden_categories = array();
            $show_protected	   = WC_PPC_Util::showing_protected_categories();

            // Get all the product categories, and check which are hidden.
            foreach ( WC_PPC_Util::to_category_visibilities( WC_PPC_Util::get_product_categories() ) as $category ) {
                if ( $category->is_private() || ( ! $show_protected && $category->is_protected() ) ) {
                    $hidden_categories[] = $category->term_id;
                }
            }

            if ( $hidden_categories && ! empty( $hidden_categories ) ) {

                foreach( $hidden_categories as $hidden_category ) {
                    $this->data['exclude_categories'][] = $hidden_category;
                }

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

                    foreach( $exclude_products as $exclude_product ) {
                        $this->data['exclude_products'][] = $exclude_product;
                    }

                }

            }

        }

        /*
         * Datafeedr WooCommerce Importer plugin
         */
        public function dfrapi_set_currency_symbol_filter( $currency_symbol, $currency ) {

            global $product;
            if ( ! is_object( $product ) || ! isset( $product ) ) {
                return $currency_symbol;
            }
            $fields = get_post_meta( $product->get_id(), '_dfrps_product', true );
            if ( empty( $fields ) ) {
                return $currency_symbol;
            }
            if ( ! isset( $fields['currency'] ) ) {
                return $currency_symbol;
            }
            $currency_symbol = dfrapi_currency_code_to_sign( $fields['currency'] );
            return $currency_symbol;

        }

        /*
         * Exclude product categories
         */
        public function filter_protected_cats_term_exclude( $exclude ) {
            if ( isset( $this->data['exclude_categories'] ) ) {
                foreach( $this->data['exclude_categories'] as $to_exclude ) {
                    $exclude[] = $to_exclude;
                }
            }
            return $exclude;
        }

        /*
         * Exclude products
         */
        public function filter_products_exclude( $exclude ) {
            if ( isset( $this->data['exclude_products'] ) ) {
                foreach( $this->data['exclude_products'] as $to_exclude ) {
                    $exclude[] = $to_exclude;
                }
            }
            return $exclude;
        }

        public function woocommerce_product_query( $query ) {

            $query_args = array(
                's'                => 'a',
                'post_type'        => 'product',
                'suppress_filters' => true,
                'fields'           => 'ids',
                'posts_per_page'   => 1
            );

            $query = new WP_Query( $query_args );
            $query_vars = $query->query_vars;

            $query_args_options = get_option( 'aws_search_query_args' );

            if ( ! $query_args_options ) {
                $query_args_options = array();
            }

            $user_role = 'non_login';

            if ( is_user_logged_in() ) {
                $user = wp_get_current_user();
                $role = ( array ) $user->roles;
                $user_role = $role[0];
            }

            $query_args_options[$user_role] = array(
                'post__not_in' => $query_vars['post__not_in'],
                'category__not_in' => $query_vars['category__not_in'],
            );

            update_option( 'aws_search_query_args', $query_args_options );

        }

    }

endif;

AWS_Integrations::instance();