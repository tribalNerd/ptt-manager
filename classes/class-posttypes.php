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
 * @method init()               Init Class Methods
 * @method posttypes()          Start Post Types Register
 * @method presets()            Start Presets Register
 * @method register()           Register Post Types
 * @method setColumns()         Set Column Names
 * @method setColumnContent()   Content Within Columns
 * @method categoryFilter()     Filter By Category Dropdown
 * @method instance()           Class Instance
 */
if ( ! class_exists( 'PTTManager_Posttypes' ) )
{
    class PTTManager_Posttypes extends PTTManager_Extended
    {
        // Holds Instance Object
        protected static $instance = NULL;

        // Post Type Single Name
        private $single = '';

        // Post Type Plural Name
        private $plural = '';


        /**
         * @about Initiate Registration
         */
        final public function init()
        {
            // Start Preset Post Type Register
            $this->presets();

            // Start Post Type Register
            $this->posttype();
        }


        /**
         * @about Start Post Types Register
         */
        final public function posttype()
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
            $data = get_option( $this->plugin_name . '_preset_posttypes' );

            // Ignore if No Preset Data
            if ( ! $data || empty( $data ) || ! is_array( $data ) ) { return; }

            // Books
            if ( isset( $data['books'] ) && ! get_option( $this->plugin_name . '_posttype_books' ) ) {
                $this->register( 'book', 'books', $data );
            }

            // Docs
            if ( isset( $data['docs'] ) && ! get_option( $this->plugin_name . '_posttype_docs' ) ) {
                $this->register( 'docs', 'docs', $data );
            }

            // FAQ
            if ( isset( $data['faq'] ) && ! get_option( $this->plugin_name . '_posttype_faq' ) ) {
                $this->register( 'faq', 'faq', $data );
            }

            // Music
            if ( isset( $data['music'] ) && ! get_option( $this->plugin_name . '_posttype_music' ) ) {
                $this->register( 'music', 'music', $data );
            }

            // Portfolio
            if ( isset( $data['portfolios'] ) && ! get_option( $this->plugin_name . '_posttype_portfolios' ) ) {
                $this->register( 'portfolio', 'portfolio', $data );
            }

            // Teams
            if ( isset( $data['teams'] ) && ! get_option( $this->plugin_name . '_posttype_teams' ) ) {
                $this->register( 'team', 'teams', $data );
            }

            // Testimonials
            if ( isset( $data['testimonials'] ) && ! get_option( $this->plugin_name . '_posttype_testimonials' )  ) {
                $this->register( 'testimonial', 'testimonials', $data );
            }

            // Videos
            if ( isset( $data['videos'] ) && ! get_option( $this->plugin_name . '_posttype_videos' ) ) {
                $this->register( 'video', 'videos', $data );
            }
        }


        /**
         * @about Register Post Type
         */
        final public function register( $single, $plural, $data = array() )
        {
            // Required
            if( ! isset( $single, $plural ) || ! $data || ! is_array( $data ) ) { return; }

            // Add New Dashicons Picker
            if ( isset( $data['dashicons_picker_posttype-add'] ) && $data['dashicons_picker_posttype-add'] != '' ) {
                $icon = esc_attr( $data['dashicons_picker_posttype-add'] );

            // Set / Update Icon
            } elseif ( isset( $data['dashicons_picker_posttype-' . $single] ) && $data['dashicons_picker_posttype-' . $single] != '' ) {
                $icon = esc_attr( $data['dashicons_picker_posttype-' . $single] );

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
            $restbase = ( $showrest && ! empty( $data['showrest'] ) && ! empty( $data['restbase'] ) ) ? $this->sanitize( $data['restbase'] ) : (bool) false;

            // Archive Slug
            $archiveslug = ( ! empty( $data['archiveslug'] ) ) ? $this->sanitize( $data['archiveslug'] ) : (bool) false;
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
            $supports       = array_merge( $author, $comments, $customfields, $editor, $excerpt, $pageattributes, $revisions, $postformats, $thumbnail, $title, $trackbacks );

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
                'capability_type'       => 'post',
                'has_archive'           => $archive,
                'hierarchical'          => true,
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
                    'add_new'               => sprintf( esc_attr_x( 'Add %1$s', $single, 'ptt-manager' ), ucfirst( $single ) ),
                    'add_new_item'          => sprintf( esc_attr__( 'Add New %1$s', 'ptt-manager' ), ucfirst( $single ) ),
                    'new_item'              => sprintf( esc_attr__( 'New %1$s', 'ptt-manager' ), ucfirst( $single ) ),
                    'edit_item'             => sprintf( esc_attr__( 'Edit %1$s', 'ptt-manager' ), ucfirst( $single ) ),
                    'view_item'             => sprintf( esc_attr__( 'View %1$s', 'ptt-manager' ), ucfirst( $single ) ),
                    'all_items'             => sprintf( esc_attr__( '%1$s', 'ptt-manager' ), ucfirst( $plural ) ),
                    'search_items'          => sprintf( esc_attr__( 'Search %1$s', 'ptt-manager' ), ucfirst( $plural ) ),
                    'parent_item_colon'     => sprintf( esc_attr__( 'Parent %1$s:', 'ptt-manager' ), ucfirst( $plural ) ),
                    'not_found'             => sprintf( esc_attr__( 'No %1$s Found.', 'ptt-manager' ), ucfirst( $plural ) ),
                    'not_found_in_trash'    => sprintf( esc_attr__( 'No %1$s Found in Trash.', 'ptt-manager' ), ucfirst( $plural ) ),
                ),
            );

            // Set Vars For Filter/Actions
            $this->single = '';
            $this->single = $single;
  
            $this->plural = '';
            $this->plural = $plural;

            // Add Column Name to UI
            //add_filter( 'manage_' . $single . '_posts_columns', array( $this, 'setColumns' ) );

            // Add Column Content to UI
            //add_action( 'manage_' . $single . '_posts_custom_column' , array( $this, 'setColumnContent' ), 10, 2 );

            // Add Categories Dropdown to UI
            //add_action( 'restrict_manage_posts', array( $this, 'categoryFilter' ) );

            // Create Post Type
            register_post_type( $this->sanitizeName( $plural ), $args );
        }


        /**
         * @about Set Column Names
         */
        final public function setColumns( $columns )
        {
            // Required
            if ( ! taxonomy_exists( $this->single ) ) { return; }

            global $post;

            // Remove Columns
            unset( $columns['title'] );
            
            // Strip the Post Type Name from the Filter Name
            $replace = str_replace( 'manage_', '', current_filter() );
            $posttype = str_replace( '_posts_columns', '', $replace );

            // Reset Column Order
            $new_columns['cb'] = '<label class="screen-reader-text" for="cb-select-' . $post->ID . '">' . 
                                    sprintf( esc_attr__( 'Select %s', 'ptt-manager' ), _draft_or_post_title() ) . '</label>' .
                                    '<input type="checkbox" name="media[]" id="cb-select-' . $post->ID . '" value="' . $post->ID . '" />';
            $new_columns['title'] = sprintf( esc_attr__( '%1$s Title', 'ptt-manager' ), ucfirst( $posttype ) );

            if ( taxonomy_exists( $this->single ) ) {
                $new_columns[$this->single] = get_taxonomy( $this->single )->labels->name;
            }

            $new_columns['author']      = esc_attr__( 'Author', 'ptt-manager' );
            $new_columns['tags']        = esc_attr__( 'Tags', 'ptt-manager' );
            $new_columns['comments']    = '<span class="vers comment-grey-bubble" title="' . esc_attr__( 'Comments', 'ptt-manager' ) . '"></span>';
            $new_columns['date']        = esc_attr_x( 'Date', 'column name', 'ptt-manager' );

            return $new_columns;
        }


        /**
         * @about Set The Content Within Columns
         */
        final public function setColumnContent( $column, $post_id )
        {
            // Required
            if ( ! taxonomy_exists( $this->single ) || ! isset( $column, $post_id ) ) { return; }

            // Categories for Columns
            $categories = get_terms( array(
                'taxonomy'      => esc_attr( $this->single ),
                'hide_empty'    => false
            ) );

            $html = array();

            foreach ( $categories as $category ) {
                if ( $category->count == 0 ) { continue; }

                $html[] = '<a href="edit.php?post_type=' . esc_attr( $this->single ) . '&' . esc_attr( $this->plural ) . '=' . esc_attr( $category->name ) . '">' . esc_attr( $category->name ) . '</a>';
            }

            echo join( ', ', $html );
        }


        /**
         * @about Filter By Category Dropdown
         */
        final public function categoryFilter()
        {
            // Required
            if ( ! taxonomy_exists( $this->single ) ) { return; }

            global $typenow;
            global $wp_query;

            if ( $typenow == $this->single ) {
                // Include Taxonomy
                $taxonomy = get_taxonomy( $this->single );
                $term = isset( $wp_query->query['term'] ) ? $wp_query->query['term'] : '';

                wp_dropdown_categories( array(
                    'show_option_all' => sprintf( esc_attr__( 'Show All %1$s', 'ptt-manager' ), $taxonomy->label ),
                    'taxonomy'        =>  $this->single,
                    'name'            =>  $this->single,
                    'orderby'         =>  'name',
                    'selected'        =>  $term,
                    'hierarchical'    =>  true,
                    'show_count'      =>  true,
                    'hide_empty'      =>  true,
                    'depth'           =>  3,
                ));
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
