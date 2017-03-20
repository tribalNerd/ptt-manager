<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }

/**
 * @about Register Custom Taxonomies
 * @url http://codex.wordpress.org/Function_Reference/register_taxonomy
 * @url https://codex.wordpress.org/Function_Reference/register_taxonomy_for_object_type
 * @action add_action( 'init', array( 'tNtePTTManager_Taxonomy', 'instance' ) );
 * 
 * @method init()           Init Class Methods
 * @method options()        Get Taxonomy Options
 * @method args()           Args for Taxonomies
 * @method register()       Register Taxonomies
 * @method instance()       Class Instance
 */
if( ! class_exists( 'PTTManager_Taxonomies' ) )
{
    class PTTManager_Taxonomies extends PTTManager_Extended
    {
        // Holds Instance Object
        protected static $instance = NULL;


        /**
         * @about Initiate Registration
         */
        final public function init()
        {
            // Register if not blocked
            if ( ! get_option( $this->plugin_name . '_taxonomy_block' ) ) {
                // Load Taxonomies
                $this->taxonomies();

                // Checkbox Listing of Post Types
                add_filter( $this->plugin_name . '_list_posttypes', array( $this, 'listPosttypes' ), 10, 1 );
            }
        }


        /**
         * @about Start Taxonomy Register
         */
        final public function options()
        {
            // Get Taxonomy Markers
            $markers = get_option( $this->plugin_name . '_taxonomy' );

            // Ignore if No Markers
            if ( ! $markers || empty( $markers ) ) { return; }

            // Get Saved Taxonomy Data & Register
            foreach( $markers as $marker ) {
                // Get Unique Record Option
                $data = get_option( $this->plugin_name . '_taxonomy_' . $marker );

                // Start Registration
                if ( isset( $data['plural'], $data['singular'] ) && ! empty( $data ) && is_array( $data ) ) {
                    $this->register( $this->sanitize( $data['plural'] ), $this->sanitize( $data['singular'] ), $data );
                }
            }
        }


        /**
         * @about Args for Taxonomies
         * @param string $plural    Plural Post Type Name
         * @param string $single    Single Post Type Name
         * @param array $data       Option Data
         */
        final private function args( $plural, $single, $data = array() )
        {
            // Required
            if( ! isset( $plural, $single ) || ! $data || ! is_array( $data ) ) { return; }

            // If checked, the taxonomy is not publicly viewable
            $public = ( ! empty( $data['public'] ) ) ? (bool) false : (bool) true;

            // If checked, do not show within WP Admin
            $showui = ( ! empty( $data['showui'] ) ) ? (bool) false : (bool) true;

            // Parent-Children (true like categories) (false like tags)
            $hierarchical = ( ! empty( $data['hierarchical'] ) ) ? (bool) false : (bool) true;

            // Set the taxonomy description if it has been defined
            $description = ( ! empty( $data['description'] ) ) ? esc_html( $this->sanitize( $data['description'] ) ) : (bool) false;

            // Taxonomy Slug
            $slug = ( ! empty( $data['slug'] ) ) ? $this->sanitizeName( $data['slug'] ) : $this->sanitizeName( $single );

            // Arguments for this taxonomy
            return array(
                'public'            => $public,
                'show_in_nav_menus' => $public,
                'query_var'         => $public,
                'show_ui'           => $showui,
                'hierarchical'      => $hierarchical,
                'description'       => $description,
                'show_admin_column' => true,
                'rewrite'           => array( 'slug' => $slug, 'with_front' => true ),
                'labels'            => array(
                    'name'              => sprintf( esc_attr_x( '%1$s', 'taxonomy general name', 'ptt-manager' ), ucfirst( $plural ) ),
                    'singular_name'     => sprintf( esc_attr_x( '%1$s', 'taxonomy singular name', 'ptt-manager' ), ucfirst( $single ) ),
                    'new_item_name'     => sprintf( esc_attr__( 'New %1$s', 'ptt-manager' ), ucfirst( $single ) ),
                    'edit_item'         => sprintf( esc_attr__( 'Edit %1$s', 'ptt-manager' ), ucfirst( $plural ) ),
                    'update_item'       => sprintf( esc_attr__( 'Update %1$s', 'ptt-manager' ), ucfirst( $plural ) ),
                    'add_new_item'      => sprintf( esc_attr__( 'Add New %1$s', 'ptt-manager' ), ucfirst( $single ) ),
                    'search_items'      => sprintf( esc_attr__( 'Search %1$s', 'ptt-manager' ), ucfirst( $plural ) ),
                    'all_items'         => sprintf( esc_attr__( 'All %1$s', 'ptt-manager' ), ucfirst( $plural ) ),
                    'parent_item'       => sprintf( esc_attr__( 'Parent %1$s', 'ptt-manager' ), ucfirst( $plural ) ),
                    'parent_item_colon' => sprintf( esc_attr__( 'Parent %1$s:', 'ptt-manager' ), ucfirst( $plural ) ),
                    'not_found'         => sprintf( esc_attr__( 'No %1$s Found', 'ptt-manager' ), ucfirst( $plural ) ),
            ) );
        }


        /**
         * @about Register Taxonomies
         * @param string $plural    Plural Post Type Name
         * @param string $single    Single Post Type Name
         * @param array $data       Option Data
         */
        final public function register( $plural, $single, $data = array() )
        {
            // Get Array Args
            $args = $this->args( $plural, $single, $data );

            // For Post Types Tab
            $posttype_array = array();

            // Post Types Tab: Loop through Selected Post Types from templates/taxonomies.php
            if ( ( isset( $data['pt'] ) && is_array( $data['pt'] ) ) ) {
                foreach ( $data['pt'] as $key => $posttype ) {
                    $posttype_array[$key] = $posttype;
                }
            } else {
                $posttype_array["page"] = "page";
            }

            // Create Taxonomy
            register_taxonomy( $this->sanitizeName( $plural ), $posttype_array, $args );

            // Add Taxonomy To Post Type
            foreach( $posttype_array as $key => $type ) {
                if ( isset( $key ) ) {
                    register_taxonomy_for_object_type( 'category', $type );
                    register_taxonomy_for_object_type( 'post_tag', $type );
                }
            }
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
