<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }

/**
 * @about Register Post Types
 * @url https://codex.wordpress.org/Function_Reference/register_post_type
 * @url https://codex.wordpress.org/Plugin_API/Action_Reference/manage_posts_custom_column
 * @url https://codex.wordpress.org/Plugin_API/Action_Reference/manage_$post_type_posts_custom_column
 * @action add_action( 'init', array( 'tNtePTTManager_Posttypes', 'instance' ) );
 * 
 * @method init()       Init Class Methods
 * @method options()    Get Post Type Options
 * @method args()       Args for Taxonomies
 * @method register()   Register Post Types
 * @method instance()   Class Instance
 */
if ( ! class_exists( 'PTTManager_Posttypes' ) )
{
    class PTTManager_Posttypes extends PTTManager_Extended
    {
        // Holds Instance Object
        protected static $instance = NULL;


        /**
         * @about Initiate Registration
         */
        final public function init()
        {
            // Register if not blocked
            if ( ! get_option( $this->plugin_name . '_posttype_block' ) ) {
                // Start Post Type Register
                $this->posttype();
            }
        }


        /**
         * @about Start Post Types Register
         */
        final public function options()
        {
            // Get Post Type Markers
            $markers = get_option( $this->plugin_name . '_posttype' );

            // Ignore if No Markers
            if ( ! $markers || empty( $markers ) || ! is_array( $markers ) ) { return; }

            // Get Saved Post Type Data & Register
            foreach( $markers as $marker ) {
                // Get Unique Record Option
                $data = get_option( $this->plugin_name . '_posttype_' . $marker );

                // Start Registration
                if ( isset( $data['plural'], $data['singular'] ) && ! empty( $data ) && is_array( $data ) ) {
                    $this->register( $this->sanitize( $data['plural'] ), $this->sanitize( $data['singular'] ), $data );
                }
            }
        }


        /**
         * @about Args for Post Types
         * @param string $plural    Plural Post Type Name
         * @param string $single    Single Post Type Name
         * @param array $data       Option Data
         */
        final private function args( $plural, $single, $data = array() )
        {
            // Required
            if( ! isset( $plural, $single ) || ! $data || ! is_array( $data ) ) { return; }

            // Add New Dashicons Picker
            if ( isset( $data['dashicons_picker_' . $this->sanitizeName( $plural )] ) ) {
                $icon = esc_attr( $data['dashicons_picker_' . $this->sanitizeName( $plural )] );

            // Else No Icon
            } else {
                $icon = (bool) false;
            }

            // If checked, the post type is not publicly viewable
            $public = ( ! empty( $data['public'] ) ) ? (bool) false : (bool) true;

            // If checked, exclude from search
            $search = ( ! empty( $data['search'] ) ) ? (bool) true : (bool) false;

            // If checked, the post type is not website viewable
            $queryable = ( ! empty( $data['queryable'] ) ) ? (bool) false : (bool) true;

            // If checked, do not show within WP Admin
            $showui = ( ! empty( $data['showui'] ) ) ? (bool) false : (bool) true;

            // Custom Menu Name
            $menuname = ( ! empty( $data['menuname'] ) ) ? $this->sanitize( $data['menuname'] ) : ucfirst( $plural );

            // Show Data Within REST API
            $showrest = ( ! empty( $data['showrest'] ) ) ? (bool) true : (bool) false;

            // REST API Slug
            $restbase = ( $showrest && ! empty( $data['showrest'] ) && ! empty( $data['restbase'] ) ) ? $this->sanitizeName( $data['restbase'] ) : (bool) false;

            // Archive Slug
            $archiveslug = ( ! empty( $data['archiveslug'] ) ) ? $this->sanitizeName( $data['archiveslug'] ) : (bool) false;
            $archive = ( empty( $data['archive'] ) ) ? $archiveslug : (bool) false;

            // Slug
            $slug = ( ! empty( $data['slug'] ) ) ? $this->sanitizeName( $data['slug'] ) : $this->sanitizeName( $plural );

            // Rewrite Slug
            $rewrite = array( 'slug' => $slug, 'with_front' => true );

            // If a menu position has been defined, use it, else use the default
            $position = ( ! empty( $data['position'] ) ) ? absint( $data['position'] ) : (bool) false;

            // Set the post type description if it has been defined
            $description = ( ! empty( $data['description'] ) ) ? esc_html( $this->sanitize( $data['description'] ) ) : (bool) false;

            // If checked add support for Registered Taxonomies
            if ( ( isset( $data['tn'] ) && is_array( $data['tn'] ) ) ) {
                // Create empty array
                $posttype_array = array();

                // Define selected taxonomies
                foreach ( $data['tn'] as $key => $taxonomy ) {
                    $posttype_array[$key] = $taxonomy;
                }
            }

            // Set taxonomies array
            $taxonomies = ( isset( $data['tn'] ) && is_array( $data['tn'] ) ) ? $posttype_array : array();

            // Supports Vars
            $author         = ( ! isset( $data['author'] ) ) ? array( 'author' => 'author' ) : array();
            $comments       = ( ! isset( $data['comments'] ) ) ? array( 'comments' => 'comments' ) : array();
            $customfields   = ( ! isset( $data['customfields'] ) ) ? array( 'custom-fields' => 'custom-fields' ) : array();
            $editor         = ( ! isset( $data['editor'] ) ) ? array( 'editor' => 'editor' ) : array();
            $excerpt        = ( ! isset( $data['excerpt'] ) ) ? array( 'excerpt' => 'excerpt' ) : array();
            $pageattributes = ( ! isset( $data['pageattributes'] ) ) ? array( 'page-attributes' => 'page-attributes' ) : array();
            $revisions      = ( ! isset( $data['revisions'] ) ) ? array( 'revisions' => 'revisions' ) : array();
            $postformats    = ( ! isset( $data['postformats'] ) ) ? array( 'post-formats' => 'post-formats' ) : array();
            $thumbnail      = ( ! isset( $data['thumbnail'] ) ) ? array( 'thumbnail' => 'thumbnail' ) : array();
            $title          = ( ! isset( $data['title'] ) ) ? array( 'title' => 'title' ) : array();
            $trackbacks     = ( ! isset( $data['trackbacks'] ) ) ? array( 'trackbacks' => 'trackbacks' ) : array();
            $support        = array_merge( $author, $comments, $customfields, $editor, $excerpt, $pageattributes, $revisions, $postformats, $thumbnail, $title, $trackbacks );
            $supports       = ( empty( $support ) ) ? (bool) false : $support;

            // Arguments for this post type
            $args = array(
                'public'                => $public,
                'show_in_nav_menus'     => $public,
                'exclude_from_search'   => $search,
                'publicly_queryable'    => $queryable,
                'query_var'             => $queryable,
                'show_ui'               => $showui,
                'show_in_menu'          => $showui,
                'show_in_admin_bar'     => $showui,
                'show_in_rest'          => $showrest,
                'rest_base'             => $restbase,
                'rewrite'               => $rewrite,
                'has_archive'           => $archive,
                'capability_type'       => 'post',
                'hierarchical'          => true,
                'map_meta_cap'          => true,
                'description'           => $description,
                'menu_position'         => $position,
                'menu_icon'             => $icon,
                'taxonomies'            => $taxonomies,
                'supports'              => $supports,
                'labels' => array(
                    'name'                  => sprintf( esc_attr_x( '%1$s', 'post type general name', 'ptt-manager' ), ucfirst( $plural ) ),
                    'singular_name'         => sprintf( esc_attr_x( '%1$s', 'post type singular name', 'ptt-manager' ), ucfirst( $single ) ),
                    'menu_name'             => sprintf( esc_attr_x( '%1$s', 'admin menu', 'ptt-manager' ), $menuname ),
                    'name_admin_bar'        => sprintf( esc_attr_x( '%1$s', 'add new on admin bar', 'ptt-manager' ), ucfirst( $single ) ),
                    'add_new'               => sprintf( esc_attr_x( 'Add %1$s', $this->sanitizeName( $single ), 'ptt-manager' ), ucfirst( $single ) ),
                    'add_new_item'          => sprintf( esc_attr__( 'Add New %1$s', 'ptt-manager' ), ucfirst( $single ) ),
                    'new_item'              => sprintf( esc_attr__( 'New %1$s', 'ptt-manager' ), ucfirst( $single ) ),
                    'edit_item'             => sprintf( esc_attr__( 'Edit %1$s', 'ptt-manager' ), ucfirst( $single ) ),
                    'view_item'             => sprintf( esc_attr__( 'View %1$s', 'ptt-manager' ), ucfirst( $single ) ),
                    'all_items'             => sprintf( esc_attr__( '%1$s', 'ptt-manager' ), ucfirst( $plural ) ),
                    'search_items'          => sprintf( esc_attr__( 'Search %1$s', 'ptt-manager' ), ucfirst( $plural ) ),
                    'parent_item_colon'     => sprintf( esc_attr__( 'Parent %1$s:', 'ptt-manager' ), ucfirst( $plural ) ),
                    'not_found'             => sprintf( esc_attr__( 'No %1$s Found.', 'ptt-manager' ), ucfirst( $plural ) ),
                    'not_found_in_trash'    => sprintf( esc_attr__( 'No %1$s Found in Trash.', 'ptt-manager' ), ucfirst( $plural ) ),
                    'archives'              => sprintf( esc_attr__( '%1$s Archives', 'ptt-manager' ), ucfirst( $single ) ),
                    'attributes'            => sprintf( esc_attr__( '%1$s Attributes', 'ptt-manager' ), ucfirst( $single ) ),
                    'featured_image'        => sprintf( esc_attr__( 'Featured %1$s Image', 'ptt-manager' ), ucfirst( $single ) ),
                    'set_featured_image'    => sprintf( esc_attr__( 'Set Featured %1$s Image', 'ptt-manager' ), ucfirst( $single ) ),
                    'remove_featured_image' => sprintf( esc_attr__( 'Remove Featured %1$s Image', 'ptt-manager' ), ucfirst( $single ) ),
                    'use_featured_image'    => sprintf( esc_attr__( 'Use As Featured %1$s Image', 'ptt-manager' ), ucfirst( $single ) ),
                    'items_list'            => sprintf( esc_attr__( '%1$s List', 'ptt-manager' ), ucfirst( $plural ) ),
                    'items_list_navigation' => sprintf( esc_attr__( '%1$s List Navigation', 'ptt-manager' ), ucfirst( $plural ) ),
                    'insert_into_item'      => sprintf( esc_attr__( 'Insert Into %1$s', 'ptt-manager' ), ucfirst( $plural ) ),
                    'uploaded_to_this_item' => sprintf( esc_attr__( 'Uploaded To %1$s', 'ptt-manager' ), ucfirst( $plural ) ),
                    'filter_items_list'     => sprintf( esc_attr__( 'Filter %1$s', 'ptt-manager' ), ucfirst( $plural ) ),
                ),
            );
        }


        /**
         * @about Register Post Type
         * @param string $plural    Plural Post Type Name
         * @param string $single    Single Post Type Name
         * @param array $data       Option Data
         */
        final public function register( $plural, $single, $data = array() )
        {
            // Get Array Args
            $args = $this->args( $plural, $single, $data );
            
            // Create Post Type
            register_post_type( $this->sanitizeName( $plural ), $args );
        }


        /**
         * @about Create Instance
         */
        public static function instance()
        {
            if ( ! self::$instance ) {
                self::$instance = new self();
                self::$instance->init();
            }

            return self::$instance;
        }
    }
}
