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
 * @method taxonomies()     Start Taxonomy Register
 * @method presets()        Start Presets Register
 * @method args()           Args for Taxonomies
 * @method registerPreset() Register Preset Taxonomies
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
                // Load Presets
                $this->presets();

                // Load Taxonomies
                $this->taxonomies();

                // Checkbox Listing of Post Types
                add_filter( $this->plugin_name . '_list_posttypes', array( $this, 'listPosttypes' ), 10, 1 );
            }
        }


        /**
         * @about Start Taxonomy Register
         */
        final public function taxonomies()
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
                if ( isset( $data['singular'], $data['plural'] ) && ! empty( $data ) && is_array( $data ) ) {
                    $this->register( $this->sanitize( $data['singular'] ), $this->sanitize( $data['plural'] ), $data );
                }
            }
        }


        /**
         * @about Start Presets Register
         */
        final public function presets()
        {
            // Get Option Data
            $data = get_option( $this->plugin_name . '_preset_taxonomies' );

            // Ignore if No Preset Data
            if ( ! $data || empty( $data ) ) { return; }

            // Books
            if ( isset( $data['books'] ) || isset( $data['books-posts'] ) || isset( $data['books-pages'] ) && ! get_option( $this->plugin_name . '_taxonomy_books' ) ) {
                $this->registerPreset( 'book', 'books', $data );
            }

            // Docs
            if ( isset( $data['docs'] ) || isset( $data['docs-posts'] ) || isset( $data['docs-pages'] ) && ! get_option( $this->plugin_name . '_taxonomy_docs' ) ) {
                $this->registerPreset( 'docs', 'docs', $data );
            }

            // FAQ
            if ( isset( $data['faq'] ) || isset( $data['faq-posts'] ) || isset( $data['faq-pages'] ) && ! get_option( $this->plugin_name . '_taxonomy_' ) && ! get_option( $this->plugin_name . '_taxonomy_faq' ) ) {
                $this->registerPreset( 'faq', 'faq', $data );
            }

            // Music
            if ( isset( $data['music'] ) || isset( $data['music-posts'] ) || isset( $data['music-pages'] ) || isset( $data['-posts'] ) || isset( $data['-pages'] ) && ! get_option( $this->plugin_name . '_taxonomy_music' ) ) {
                $this->registerPreset( 'music', 'music', $data );
            }

            // Portfolio
            if ( isset( $data['portfolio'] ) || isset( $data['portfolio-posts'] ) || isset( $data['portfolio-pages'] ) && ! get_option( $this->plugin_name . '_taxonomy_portfolio' ) ) {
                $this->registerPreset( 'portfolio', 'portfolio', $data );
            }

            // Teams
            if ( isset( $data['teams'] ) || isset( $data['teams-posts'] ) || isset( $data['teams-pages'] ) && ! get_option( $this->plugin_name . '_taxonomy_teams' ) ) {
                $this->registerPreset( 'team', 'teams', $data );
            }

            // Testimonials
            if ( isset( $data['testimonials'] ) || isset( $data['testimonials-posts'] ) || isset( $data['testimonials-pages'] ) && ! get_option( $this->plugin_name . '_taxonomy_testimonials' ) ) {
                $this->registerPreset( 'testimonial', 'testimonials', $data );
            }

            // Videos
            if ( isset( $data['videos'] ) || isset( $data['videos-posts'] ) || isset( $data['videos-pages'] ) && ! get_option( $this->plugin_name . '_taxonomy_videos' ) ) {
                $this->registerPreset( 'video', 'videos', $data );
            }
        }


        /**
         * @about Args for Taxonomies
         */
        final public function args( $single, $plural, $data = array() )
        {
            // Required
            if( ! isset( $single, $plural ) || ! $data || ! is_array( $data ) ) { return; }

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
                'rewrite'           => array( 'slug' => esc_attr( $slug ), 'with_front' => true ),
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
         * @about Register Preset Taxonomies
         */
        final public function registerPreset( $single, $plural, $data = array() )
        {
            // Get Array Args
            $args = $this->args( $single, $plural, $data );

            // Presets: Add to Posts
            if ( isset( $data[$plural . '-posts'] ) ) {
                $post_type = array( 'post' );
            } else {
                $post_type = array();
            }

            // Presets: Add to Pages
            if ( isset( $data[$plural . '-pages'] ) ) {
                $page_type = array( 'page' );
            } else {
                $page_type = array();
            }

            // Presets: Add to Selected Post Type
            if ( isset( $data[$plural] ) ) {
                $selected_type = array( $plural );
            } else {
                $selected_type = array();
            }
 
            // Merge Arrays
            $object_types = array_merge( $post_type, $page_type, $selected_type );

            // Create Taxonomy
            register_taxonomy( $this->sanitizeName( $plural ), $object_types, $args );

            // Add Taxonomy To Post Type
            foreach( $object_types as $key => $type ) {
                if ( isset( $key ) ) {
                    register_taxonomy_for_object_type( 'category', $type );
                    register_taxonomy_for_object_type( 'post_tag', $type );
                }
            }
        }


        /**
         * @about Register Taxonomies
         */
        final public function register( $single, $plural, $data = array() )
        {
            // Get Array Args
            $args = $this->args( $single, $plural, $data );

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
