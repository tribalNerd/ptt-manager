<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }

/**
 * @about Register Custom Taxonomies
 * @url http://codex.wordpress.org/Function_Reference/register_taxonomy
 * @url https://codex.wordpress.org/Function_Reference/register_taxonomy_for_object_type
 * @call new PTTManager_Taxonomies( $option_data );
 * 
 * @method __construct()    Required Settings
 * @method register()       Register Taxonomies
 * @method args()           Args for Taxonomies
 * @method labels()         Labels for Taxonomies
 * @method posttypes()      Assigned Post Types
 * @method slug()           Taxonomy Slug
 * @method sanitize()       Sanitize Text Strings
 */
if( ! class_exists( 'PTTManager_Taxonomies' ) )
{
    class PTTManager_Taxonomies
    {
        // Option Data Array
        private $data = array();

        // Plural label name
        private $plural;

        // Singular label name
        private $single;


        /**
         * @about Required Taxonomy Settings
         * @param array $option_data Saved Option Taxonomy Data
         */
        final public function __construct( array $option_data )
        {
            // Required
            if ( ! $option_data || ! is_array( $option_data ) ) {
                wp_die( __( 'The $option_data array is required!', 'ptt-manager' ) );
            }

            // Set Option Data Varabile
            $this->data = $option_data;

            if ( empty( $this->data['plural'] ) ) {
                wp_die( __( 'The taxonomy plural name is missing and required!', 'ptt-manager' ) );
            }

            if ( empty( $this->data['singular'] ) ) {
                wp_die( __( 'The taxonomy singular name is missing and required!', 'ptt-manager' ) );
            }

            // Set Plural and Single Taxonomy Name
            $this->plural   = $this->sanitize( $this->data['plural'] );
            $this->single   = $this->sanitize( $this->data['singular'] );

            // Register Taxonomies
            add_action( 'init', array( $this, 'register' ) );
        }


        /**
         * @about Register Taxonomy
         */
        final public function register()
        {
            register_taxonomy( $this->sanitize( $this->plural, true ), $this->posttypes(), $this->args() );

            // Add Taxonomy To Post Type
            foreach( $this->posttypes() as $key => $type ) {
                if ( isset( $key ) ) {
                    register_taxonomy_for_object_type( 'category', $type );
                    register_taxonomy_for_object_type( 'post_tag', $type );
                }
            }
        }


        /**
         * @about Taxonomy Arguments Array
         * @return array Taxonomy Arguments
         */
        final private function args()
        {
            return array(
                'public'            => ( ! empty( $this->data['public'] ) ) ? (bool) false : (bool) true,
                'show_in_nav_menus' => ( ! empty( $this->data['public'] ) ) ? (bool) false : (bool) true,
                'query_var'         => ( ! empty( $this->data['public'] ) ) ? (bool) false : (bool) true,
                'show_ui'           => ( ! empty( $this->data['showui'] ) ) ? (bool) false : (bool) true,
                'hierarchical'      => ( ! empty( $this->data['hierarchical'] ) ) ? (bool) false : (bool) true,
                'description'       => ( ! empty( $this->data['description'] ) ) ? $this->sanitize( $this->data['description'] ) : (bool) false,
                'rewrite'           => array( 'slug' => $this->slug(), 'with_front' => true ),
                'show_admin_column' => true,
                'labels'            => $this->labels()
            );
        }


        /**
         * @about Taxonomy Labels Array
         * @return array Taxonomy Labels
         */
        final private function labels()
        {
            return array(
                'name'              => sprintf( esc_attr_x( '%1$s', 'taxonomy general name', 'ptt-manager' ), ucfirst( $this->plural ) ),
                'singular_name'     => sprintf( esc_attr_x( '%1$s', 'taxonomy singular name', 'ptt-manager' ), ucfirst( $this->single ) ),
                'new_item_name'     => sprintf( esc_attr__( 'New %1$s', 'ptt-manager' ), ucfirst( $this->single ) ),
                'edit_item'         => sprintf( esc_attr__( 'Edit %1$s', 'ptt-manager' ), ucfirst( $this->plural ) ),
                'update_item'       => sprintf( esc_attr__( 'Update %1$s', 'ptt-manager' ), ucfirst( $this->plural ) ),
                'add_new_item'      => sprintf( esc_attr__( 'Add New %1$s', 'ptt-manager' ), ucfirst( $this->single ) ),
                'search_items'      => sprintf( esc_attr__( 'Search %1$s', 'ptt-manager' ), ucfirst( $this->plural ) ),
                'all_items'         => sprintf( esc_attr__( 'All %1$s', 'ptt-manager' ), ucfirst( $this->plural ) ),
                'parent_item'       => sprintf( esc_attr__( 'Parent %1$s', 'ptt-manager' ), ucfirst( $this->plural ) ),
                'parent_item_colon' => sprintf( esc_attr__( 'Parent %1$s:', 'ptt-manager' ), ucfirst( $this->plural ) ),
                'not_found'         => sprintf( esc_attr__( 'No %1$s Found', 'ptt-manager' ), ucfirst( $this->plural ) ),
            );
        }


        /**
         * @about Assigned Post Types
         * @return array $posttypes Selected Post Types
         */
        final private function posttypes()
        {
            // For Post Types Tab
            $posttypes = array();

            // Post Types Tab: Loop through Selected Post Types from templates/taxonomies.php
            if ( ( isset( $this->data['pt'] ) && is_array( $this->data['pt'] ) ) ) {
                foreach ( $this->data['pt'] as $key => $posttype ) {
                    $posttypes[$key] = $posttype;
                }
            } else {
                $posttypes["page"] = "page";
            }

            return $posttypes;
        }


        /**
         * @about Taxonomy Slug
         * @return string Slug Name
         */
        final private function slug()
        {
            return ( ! empty( $this->data['slug'] ) ) ? $this->sanitize( $this->data['slug'], true ) : $this->sanitize( $this->single, true );
        }


        /**
         * @about Sanitize Strings
         * @param string $string Filter var, sanitize string, strip, sanitize_text_field()
         * @param bool   $strict True to clear sapces, underscores to dashes, lowercase string
         * @default Strip tags, line breaks, tabs, whitespace, octets, validate UTF-8, < to entities
         * @strict  Strip tags, line breaks, tabs, whitespace, octets, validate UTF-8, < to entities, lowercase, replace spaces and _ with -
         * @return string Sanitized Item
         */
        final public function sanitize( $string, $strict = false )
        {
            $filtered = sanitize_text_field( filter_var( $string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_BACKTICK ) );

            if ( $strict ) {
                // Clear spaces, underscores to dashes, lowercase string
                $sanitized = preg_replace( '/\s/', '', str_replace( '_', '-', strtolower( $filtered ) ) );
            } else {
                $sanitized = $filtered;
            }

            return $sanitized;
        }
    }
}
