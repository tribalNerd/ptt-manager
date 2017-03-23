<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }

/**
 * @about Register Post Types
 * @url https://codex.wordpress.org/Function_Reference/register_post_type
 * @call new PTTManager_Posttypes( $option_data );
 * 
 * @method __construct()    Required Settings
 * @method register()       Register Post Types
 * @method args()           Args for Post Types
 * @method labels()         Labels for Post Types
 * @method archive()        Archive Slug / URL
 * @method dashicon()       Selected Wordpress Dashicon
 * @method menuname()       Post Type Menu Name
 * @method slug()           Post Type Slug
 * @method supports()       Post Type Support
 * @method taxonomies()     Assigned Taxonomies
 * @method sanitize()       Sanitize Text Strings
 */
if ( ! class_exists( 'PTTManager_Posttypes' ) )
{
    class PTTManager_Posttypes
    {
        // Option Data Array
        private $data = array();

        // Plural label name
        private $plural;

        // Singular label name
        private $single;


        /**
         * @about Required Post Type Settings
         * @param array $option_data Saved Option Post Type Data
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
                wp_die( __( 'The post type plural name is missing and required!', 'ptt-manager' ) );
            }

            if ( empty( $this->data['singular'] ) ) {
                wp_die( __( 'The post type singular name is missing and required!', 'ptt-manager' ) );
            }

            // Set Plural and Single Post Type Name
            $this->plural   = $this->sanitize( $this->data['plural'] );
            $this->single   = $this->sanitize( $this->data['singular'] );

            // Register Post Types
            add_action( 'init', array( $this, 'register' ) );
        }


        /**
         * @about Register Post Type
         */
        final public function register()
        {
            register_post_type( $this->sanitize( $this->plural, true ), $this->args() );
        }


        /**
         * @about Post Type Arguments Array
         * @return array Post Type Arguments
         */
        final private function args()
        {
            return array(
                'public'                => ( ! empty( $this->data['public'] ) ) ? (bool) false : (bool) true,
                'show_in_nav_menus'     => ( ! empty( $this->data['public'] ) ) ? (bool) false : (bool) true,
                'exclude_from_search'   => ( ! empty( $this->data['search'] ) ) ? (bool) true : (bool) false,
                'publicly_queryable'    => ( ! empty( $this->data['queryable'] ) ) ? (bool) false : (bool) true,
                'query_var'             => ( ! empty( $this->data['queryable'] ) ) ? (bool) false : (bool) true,
                'show_ui'               => ( ! empty( $this->data['showui'] ) ) ? (bool) false : (bool) true,
                'show_in_menu'          => ( ! empty( $this->data['showui'] ) ) ? (bool) false : (bool) true,
                'show_in_admin_bar'     => ( ! empty( $this->data['showui'] ) ) ? (bool) false : (bool) true,
                'show_in_rest'          => ( ! empty( $this->data['showrest'] ) ) ? (bool) true : (bool) false,
                'description'           => ( ! empty( $this->data['description'] ) ) ? $this->sanitize( $this->data['description'] ) : (bool) false,
                'rest_base'             => ( ! empty( $this->data['restbase'] ) ) ? $this->sanitize( $this->data['restbase'], true ) : (bool) false,
                'menu_position'         => ( ! empty( $this->data['position'] ) ) ? absint( $this->data['position'] ) : (bool) false,
                'rewrite'               => array( 'slug' => $this->slug(), 'with_front' => true ),
                'taxonomies'            => $this->taxonomies(),
                'menu_icon'             => $this->dashicon(),
                'supports'              => $this->supports(),
                'has_archive'           => $this->archive(),
                'labels'                => $this->labels(),
                'capability_type'       => 'post',
                'hierarchical'          => true,
                'map_meta_cap'          => true
            );
        }


        /**
         * @about Post Type Labels Array
         * @return array Post Type Labels
         */
        final private function labels()
        {
            return array(
                'name'                  => sprintf( esc_attr_x( '%1$s', 'post type general name', 'ptt-manager' ), ucfirst( $this->plural ) ),
                'singular_name'         => sprintf( esc_attr_x( '%1$s', 'post type singular name', 'ptt-manager' ), ucfirst( $this->single ) ),
                'menu_name'             => sprintf( esc_attr_x( '%1$s', 'admin menu', 'ptt-manager' ), $this->menuname() ),
                'name_admin_bar'        => sprintf( esc_attr_x( '%1$s', 'add new on admin bar', 'ptt-manager' ), ucfirst( $this->single ) ),
                'add_new'               => sprintf( esc_attr_x( 'Add %1$s', $this->sanitize( $this->single, true ), 'ptt-manager' ), ucfirst( $this->single ) ),
                'add_new_item'          => sprintf( esc_attr__( 'Add New %1$s', 'ptt-manager' ), ucfirst( $this->single ) ),
                'new_item'              => sprintf( esc_attr__( 'New %1$s', 'ptt-manager' ), ucfirst( $this->single ) ),
                'edit_item'             => sprintf( esc_attr__( 'Edit %1$s', 'ptt-manager' ), ucfirst( $this->single ) ),
                'view_item'             => sprintf( esc_attr__( 'View %1$s', 'ptt-manager' ), ucfirst( $this->single ) ),
                'all_items'             => sprintf( esc_attr__( '%1$s', 'ptt-manager' ), ucfirst( $this->plural ) ),
                'search_items'          => sprintf( esc_attr__( 'Search %1$s', 'ptt-manager' ), ucfirst( $this->plural ) ),
                'parent_item_colon'     => sprintf( esc_attr__( 'Parent %1$s:', 'ptt-manager' ), ucfirst( $this->plural ) ),
                'not_found'             => sprintf( esc_attr__( 'No %1$s Found.', 'ptt-manager' ), ucfirst( $this->plural ) ),
                'not_found_in_trash'    => sprintf( esc_attr__( 'No %1$s Found in Trash.', 'ptt-manager' ), ucfirst( $this->plural ) ),
                'archives'              => sprintf( esc_attr__( '%1$s Archives', 'ptt-manager' ), ucfirst( $this->single ) ),
                'attributes'            => sprintf( esc_attr__( '%1$s Attributes', 'ptt-manager' ), ucfirst( $this->single ) ),
                'featured_image'        => sprintf( esc_attr__( 'Featured %1$s Image', 'ptt-manager' ), ucfirst( $this->single ) ),
                'set_featured_image'    => sprintf( esc_attr__( 'Set Featured %1$s Image', 'ptt-manager' ), ucfirst( $this->single ) ),
                'remove_featured_image' => sprintf( esc_attr__( 'Remove Featured %1$s Image', 'ptt-manager' ), ucfirst( $this->single ) ),
                'use_featured_image'    => sprintf( esc_attr__( 'Use As Featured %1$s Image', 'ptt-manager' ), ucfirst( $this->single ) ),
                'items_list'            => sprintf( esc_attr__( '%1$s List', 'ptt-manager' ), ucfirst( $this->plural ) ),
                'items_list_navigation' => sprintf( esc_attr__( '%1$s List Navigation', 'ptt-manager' ), ucfirst( $this->plural ) ),
                'insert_into_item'      => sprintf( esc_attr__( 'Insert Into %1$s', 'ptt-manager' ), ucfirst( $this->plural ) ),
                'uploaded_to_this_item' => sprintf( esc_attr__( 'Uploaded To %1$s', 'ptt-manager' ), ucfirst( $this->plural ) ),
                'filter_items_list'     => sprintf( esc_attr__( 'Filter %1$s', 'ptt-manager' ), ucfirst( $this->plural ) ),
            );
        }


        /**
         * @about Archive Slug / URL
         * @return mixed Archive slug name or false
         */
        final private function archive()
        {
            return ( empty( $this->data['archive'] ) ) ? ( ! empty( $this->data['archiveslug'] ) ) ? $this->sanitize( $this->data['archiveslug'], true ) : (bool) false : (bool) false;
        }


        /**
         * @about Selected Wordpress Dashicon
         * @return string Dashicon Name
         */
        final private function dashicon()
        {
            return ( isset( $this->data['dashicons_picker_' . $this->sanitize( $this->plural, true )] ) ) ? $this->data['dashicons_picker_' . $this->sanitize( $this->plural, true )] : (bool) false;
        }


        /**
         * @about Post Type Menu Name
         * @return string Menu Name
         */
        final private function menuname()
        {
            return ( ! empty( $this->data['menuname'] ) ) ? $this->sanitize( $this->data['menuname'] ) : ucfirst( $this->plural );
        }


        /**
         * @about Post Type Slug
         * @return string Slug Name
         */
        final private function slug()
        {
            return ( ! empty( $this->data['slug'] ) ) ? $this->sanitize( $this->data['slug'], true ) : $this->sanitize( $this->plural, true );
        }


        /**
         * @about Post Type Support
         * @return array Selected Supported Items
         */
        final private function supports()
        {
            // Supports Vars
            $author         = ( ! isset( $this->data['author'] ) ) ? array( 'author' => 'author' ) : array();
            $comments       = ( ! isset( $this->data['comments'] ) ) ? array( 'comments' => 'comments' ) : array();
            $customfields   = ( ! isset( $this->data['customfields'] ) ) ? array( 'custom-fields' => 'custom-fields' ) : array();
            $editor         = ( ! isset( $this->data['editor'] ) ) ? array( 'editor' => 'editor' ) : array();
            $excerpt        = ( ! isset( $this->data['excerpt'] ) ) ? array( 'excerpt' => 'excerpt' ) : array();
            $pageattributes = ( ! isset( $this->data['pageattributes'] ) ) ? array( 'page-attributes' => 'page-attributes' ) : array();
            $revisions      = ( ! isset( $this->data['revisions'] ) ) ? array( 'revisions' => 'revisions' ) : array();
            $postformats    = ( ! isset( $this->data['postformats'] ) ) ? array( 'post-formats' => 'post-formats' ) : array();
            $thumbnail      = ( ! isset( $this->data['thumbnail'] ) ) ? array( 'thumbnail' => 'thumbnail' ) : array();
            $title          = ( ! isset( $this->data['title'] ) ) ? array( 'title' => 'title' ) : array();
            $trackbacks     = ( ! isset( $this->data['trackbacks'] ) ) ? array( 'trackbacks' => 'trackbacks' ) : array();
            $support        = array_merge( $author, $comments, $customfields, $editor, $excerpt, $pageattributes, $revisions, $postformats, $thumbnail, $title, $trackbacks );

            return ( empty( $support ) ) ? (bool) false : $support;
        }


        /**
         * @about Assigned Taxonomies
         * @return array Selected Taxonomies
         */
        final private function taxonomies()
        {
            // If checked add support for Registered Taxonomies
            if ( ( isset( $this->data['tn'] ) && is_array( $this->data['tn'] ) ) ) {
                // Create empty array
                $posttype_array = array();

                // Define selected taxonomies
                foreach ( $this->data['tn'] as $key => $taxonomy ) {
                    $posttype_array[$key] = $taxonomy;
                }
            }
 
            return ( isset( $this->data['tn'] ) && is_array( $this->data['tn'] ) ) ? $posttype_array : array();
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
