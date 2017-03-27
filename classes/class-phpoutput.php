<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }

/**
 * @about Output PHP For Post Types and Taxonomies
 * @action PTTManager_PHPOutput::instance();
 * 
 * @method init()       Init Class Methods
 * @method display()    Get Post Type / Taxonomy Data For Display
 * @method posttypes()  PHP Output Post Types
 * @method taxonomies() PHP Output Taxonomies
 * @method textdomain() Get Theme Text Domain
 * @method addQuotes()  Add Quotes Around Array Elements
 * @method instance()   Class Instance
 */
if( ! class_exists( 'PTTManager_PHPOutput' ) )
{
    class PTTManager_PHPOutput extends PTTManager_Extended
    {
        // Holds Instance Object
        protected static $instance = NULL;


        /**
         * @about Initiate Registration
         */
        final public function init()
        {
            // Output PHP Code
            // @call apply_filters( $this->plugin_name . '_php', 'posttypes' );
            // @call apply_filters( $this->plugin_name . '_php', 'taxonomies' );
            // @see templates/tools.php
            add_filter( $this->plugin_name . '_php', array( $this, 'display' ), 10, 1 );
        }


        /**
         * @about Get Post Type / Taxonomy Data For Display
         * @param string $type Output for "posttypes" or "taxonomies"
         */
        final public function display( $type )
        {
            // Post Type Data
            if ( ! empty( $type ) && $type == 'posttypes' ) {
                // Get Post Type Markers
                $types = get_option( $this->option_name . 'posttype' );

                // Ignore if No Markers
                if ( ! $types || empty( $types ) ) { return; }

                // Get Saved Post Type Data & Register
                foreach( $types as $type ) {
                    // Get Unique Record Option
                    $data = get_option( $this->option_name . 'posttype_' . $type );

                    // Start Registration
                    if ( isset( $data['plural'], $data['singular'] ) ) {
                        $this->posttypes( $this->sanitize( $data['plural'] ), $this->sanitize( $data['singular'] ), $data );
                    }
                }
            } 

            // Taxonomy Data
            if ( ! empty( $type ) && $type == 'taxonomies' ) {
                // Get Post Type Markers
                $taxonomies = get_option( $this->option_name . 'taxonomy' );

                // Ignore if No Markers
                if ( ! $taxonomies || empty( $taxonomies ) ) { return; }

                // Get Saved Post Type Data & Register
                foreach( $taxonomies as $taxonomy ) {
                    // Get Unique Record Option
                    $data = get_option( $this->option_name . 'taxonomy_' . $taxonomy );

                    // Start Registration
                    if ( isset( $data['plural'], $data['singular'] ) ) {
                        $this->taxonomies( $this->sanitize( $data['plural'] ), $this->sanitize( $data['singular'] ), $data );
                    }
                }
            } 
        }


        /**
         * @about Output Post Types
         * @param string $plural    Plural Post Type Name
         * @param string $single    Single Post Type Name
         * @param array $data       Option Data
         */
        final public function posttypes( $plural, $single, $data = array() )
        {
            // Required
            if( ! isset( $plural, $single ) || ! $data || ! is_array( $data ) ) { return; }

            // Add New Dashicons Picker
            if ( isset( $data['dashicons_picker_posttype-' . $this->sanitizeName( $plural )] ) ) {
                $icon = esc_attr( $data['dashicons_picker_posttype-' . $this->sanitizeName( $plural )] );

            // Else No Icon
            } else {
                $icon = "null";
            }

            // If checked, the post type is not publicly viewable
            $public = ( ! empty( $data['public'] ) ) ? 'false' : 'true';

            // If checked, exclude from search
            $search = ( ! empty( $data['search'] ) ) ? 'true' : 'false';

            // If checked, the post type is not website viewable
            $queryable = ( ! empty( $data['queryable'] ) ) ? 'false' : 'true';

            // If checked, do not show within WP Admin
            $showui = ( ! empty( $data['showui'] ) ) ? 'false' : 'true';

            // Custom Menu Name
            $menuname = ( ! empty( $data['menuname'] ) ) ? sanitize_text_field( $data['menuname'] ) : ucfirst( $plural );

            // Show Data Within REST API
            $showrest = ( ! empty( $data['showrest'] ) ) ? 'true' : 'false';

            // REST API Slug
            $restbase = ( $showrest && ! empty( $data['showrest'] ) && ! empty( $data['restbase'] ) ) ? $this->sanitizeName( $data['restbase'] ) : '""';

            // Archive Slug
            $archiveslug = ( ! empty( $data['archiveslug'] ) ) ? $this->sanitizeName( $data['archiveslug'] ) : 'false';
            $archive = ( empty( $data['archive'] ) ) ? $this->addQuotes( $archiveslug ) : 'false';

            // Slug
            $slug = ( ! empty( $data['slug'] ) ) ? $this->sanitizeName( $data['slug'] ) : $this->sanitizeName( $plural );

            // Rewrite Slug
            $rewrite = "array( 'slug' => '" . $slug . "', 'with_front' => true )";

            // If a menu position has been defined, use it, else use the default
            $position = ( ! empty( $data['position'] ) ) ? absint( $data['position'] ) : 'false';

            // Set the post type description if it has been defined
            $description = ( ! empty( $data['description'] ) ) ? esc_html( $this->sanitize( $data['description'] ) ) : 'false';

            // If checked add support for Registered Taxonomies
            if ( ( isset( $data['tn'] ) && is_array( $data['tn'] ) ) ) {
                // Create empty array
                $posttype_array = array();

                // Define selected taxonomies
                foreach ( $data['tn'] as $key => $taxonomy ) {
                    $posttype_array[$key] = $taxonomy;
                }
            }

            $taxonomies = ( isset( $data['tn'] ) && is_array( $data['tn'] ) ) ? $posttype_array : array();

            // Supports Vars
            $author         = ( empty( $data['author'] ) ) ? array( 'author' => 'author' ) : array();
            $comments       = ( empty( $data['comments'] ) ) ? array( 'comments' => 'comments' ) : array();
            $customfields   = ( empty( $data['customfields'] ) ) ? array( 'custom-fields' => 'custom-fields' ) : array();
            $editor         = ( empty( $data['editor'] ) ) ? array( 'editor' => 'editor' ) : array();
            $excerpt        = ( empty( $data['excerpt'] ) ) ? array( 'excerpt' => 'excerpt' ) : array();
            $pageattributes = ( empty( $data['pageattributes'] ) ) ? array( 'page-attributes' => 'page-attributes' ) : array();
            $revisions      = ( empty( $data['revisions'] ) ) ? array( 'revisions' => 'revisions' ) : array();
            $postformats    = ( empty( $data['postformats'] ) ) ? array( 'post-formats' => 'post-formats' ) : array();
            $thumbnail      = ( empty( $data['thumbnail'] ) ) ? array( 'thumbnail' => 'thumbnail' ) : array();
            $title          = ( empty( $data['title'] ) ) ? array( 'title' => 'title' ) : array();
            $trackbacks     = ( empty( $data['trackbacks'] ) ) ? array( 'trackbacks' => 'trackbacks' ) : array();
            $support        = array_merge( $author, $comments, $customfields, $editor, $excerpt, $pageattributes, $revisions, $postformats, $thumbnail, $title, $trackbacks );
            $supports       = ( empty( $support ) ) ? 'false' : "array( " . implode( ', ', array_map( array( $this, 'addQuotes' ), $support ) ) . " )";
?>

/**
 * <?php echo ucfirst( $plural );?> Post Type
 */
function pttmanger_posttype_<?php echo $plural;?>() {
    // Arguments For <?php echo ucfirst( $plural );?> Post Type
    $args = array(
        'public' => <?php echo $public;?>,
        'show_in_nav_menus' => <?php echo $public;?>,
        'exclude_from_search' => <?php echo $search;?>,
        'publicly_queryable' => <?php echo $queryable;?>,
        'query_var' => <?php echo $queryable;?>,
        'show_ui' => <?php echo $showui;?>,
        'show_in_menu' => <?php echo $showui;?>,
        'show_in_admin_bar' => <?php echo $showui;?>,
        'show_in_rest' => <?php echo $showrest;?>,
        'rest_base' => <?php echo $restbase;?>,
        'rewrite' => <?php echo $rewrite;?>,
        'has_archive' => <?php echo $archive;?>,
        'capability_type' => 'post',
        'hierarchical' => true,
        'map_meta_cap' => true,
        'description' => '<?php echo $description;?>',
        'menu_position' => <?php echo $position;?>,
        'menu_icon' => <?php echo $icon;?>,
        'taxonomies' => array(<?php echo implode( ', ', array_map( array( $this, 'addQuotes' ), $taxonomies ) );?>),
        'supports' => <?php echo $supports;?>,
        'labels' => array(
            'name' => _x( '<?php echo ucfirst( $plural );?>', 'post type general name', '<?php echo $this->textdomain();?>' ),
            'singular_name' => _x( '<?php echo ucfirst( $single );?>', 'post type singular name', '<?php echo $this->textdomain();?>' ),
            'menu_name' => _x( '<?php echo $menuname;?>', 'admin menu', '<?php echo $this->textdomain();?>' ),
            'name_admin_bar' => _x( '<?php echo ucfirst( $single );?>', 'add new on admin bar', '<?php echo $this->textdomain();?>' ),
            'add_new' => _x( 'Add New', '<?php echo parent::sanitizeName( $single );?>', '<?php echo $this->textdomain();?>' ),
            'add_new_item' => __( 'Add New <?php echo ucfirst( $single );?>', '<?php echo $this->textdomain();?>' ),
            'new_item' => __( 'New <?php echo ucfirst( $single );?>', '<?php echo $this->textdomain();?>' ),
            'edit_item' => __( 'Edit <?php echo ucfirst( $single );?>', '<?php echo $this->textdomain();?>' ),
            'view_item' => __( 'View <?php echo ucfirst( $single );?>', '<?php echo $this->textdomain();?>' ),
            'all_items' => __( '<?php echo ucfirst( $plural );?>', '<?php echo $this->textdomain();?>' ),
            'search_items' => __( 'Search <?php echo ucfirst( $plural );?>', '<?php echo $this->textdomain();?>' ),
            'parent_item_colon' => __( 'Parent <?php echo ucfirst( $plural );?>:', '<?php echo $this->textdomain();?>' ),
            'not_found' => __( 'No <?php echo ucfirst( $plural );?> Found.', '<?php echo $this->textdomain();?>' ),
            'not_found_in_trash' => __( 'No <?php echo ucfirst( $plural );?> Found in Trash.', '<?php echo $this->textdomain();?>' ),
            'archives' => __( '<?php echo ucfirst( $single );?> Archives', '<?php echo $this->textdomain();?>' ),
            'attributes' => __( '<?php echo ucfirst( $single );?> Attributes', '<?php echo $this->textdomain();?>' ),
            'featured_image' => __( 'Featured <?php echo ucfirst( $single );?> Image', '<?php echo $this->textdomain();?>' ),
            'set_featured_image' => __( 'Set Featured <?php echo ucfirst( $single );?> Image', '<?php echo $this->textdomain();?>' ),
            'remove_featured_image' => __( 'Remove Featured <?php echo ucfirst( $single );?> Image', '<?php echo $this->textdomain();?>' ),
            'use_featured_image' => __( 'Use As Featured <?php echo ucfirst( $single );?> Image', '<?php echo $this->textdomain();?>' ),
            'items_list' => __( '<?php echo ucfirst( $plural );?> List', '<?php echo $this->textdomain();?>' ),
            'items_list_navigation' => __( '<?php echo ucfirst( $plural );?> List Navigation', '<?php echo $this->textdomain();?>' ),
            'insert_into_item' => __( 'Insert Into <?php echo ucfirst( $plural );?>', '<?php echo $this->textdomain();?>' ),
            'uploaded_to_this_item' => __( 'Uploaded To <?php echo ucfirst( $plural );?>', '<?php echo $this->textdomain();?>' ),
            'filter_items_list' => __( 'Filter <?php echo ucfirst( $plural );?>', '<?php echo $this->textdomain();?>' ),
        ),
    );

    // Register Post Type
    register_post_type( '<?php echo parent::sanitizeName( $plural );?>', $args );
}

add_action( 'init', 'pttmanger_posttype_<?php echo parent::sanitizeName( $plural );?>' );




<?php }


        /**
         * @about Output Taxonomies
         * @param string $plural    Plural Post Type Name
         * @param string $single    Single Post Type Name
         * @param array $data       Option Data
         */
        final public function taxonomies( $plural, $single, $data = array() )
        {
            // Required
            if( ! isset( $plural, $single ) || ! $data || ! is_array( $data ) ) { return; }
 
            // If checked, the taxonomy is not publicly viewable
            $public = ( ! empty( $data['public'] ) ) ? 'false' : 'true';

            // If checked, do not show within WP Admin
            $showui = ( ! empty( $data['showui'] ) ) ? 'false' : 'true';

            // Parent-Children (true like categories) (false like tags)
            $hierarchical = ( ! empty( $data['hierarchical'] ) ) ? 'false' : 'true';
            
            // Taxonomy Description
            $description = ( ! empty( $data['description'] ) ) ? esc_html( $this->sanitize( $data['description'] ) ) : 'false';

            // Taxonomy Slug
            $slug = ( ! empty( $data['slug'] ) ) ? $this->sanitizeName( $data['slug'] ) : $this->sanitizeName( $single );
?>

/**
 * <?php echo ucfirst( $plural );?> Taxonomy
 */
function pttmanger_taxonomy_<?php echo $plural;?>() {
    // Arguments For <?php echo ucfirst( $plural );?> Taxonomy
    $args = array(
        'public' => <?php echo $public;?>,
        'show_in_nav_menus' => <?php echo $public;?>,
        'query_var' => <?php echo $public;?>,
        'show_ui' => <?php echo $showui;?>,
        'hierarchical' => <?php echo $hierarchical;?>,
        'description' => '<?php echo $description;?>',
        'rewrite' => array( 'slug' => '<?php echo $slug;?>', 'with_front' => true ),
        'labels' => array(
            'name' => _x( '<?php echo ucfirst( $plural );?>', 'taxonomy general name', '<?php echo $this->textdomain();?>' ),
            'singular_name' => _x( '<?php echo ucfirst( $single );?>', 'taxonomy singular name', '<?php echo $this->textdomain();?>' ),
            'new_item_name' => __( 'New <?php echo ucfirst( $single );?>', '<?php echo $this->textdomain();?>' ),
            'edit_item' => __( 'Edit <?php echo ucfirst( $plural );?>', '<?php echo $this->textdomain();?>' ),
            'update_item' => __( 'Update <?php echo ucfirst( $plural );?>', '<?php echo $this->textdomain();?>' ),
            'add_new_item' => __( 'Add New <?php echo ucfirst( $single );?>', '<?php echo $this->textdomain();?>' ),
            'search_items' => __( 'Search <?php echo ucfirst( $plural );?>', '<?php echo $this->textdomain();?>' ),
            'all_items' => __( 'All <?php echo ucfirst( $plural );?>', '<?php echo $this->textdomain();?>' ),
            'parent_item' => __( 'Parent <?php echo ucfirst( $plural );?>', '<?php echo $this->textdomain();?>' ),
            'parent_item_colon' => __( 'Parent <?php echo ucfirst( $plural );?>:', '<?php echo $this->textdomain();?>' ),
            'not_found' => __( 'No <?php echo ucfirst( $plural );?> Found', '<?php echo $this->textdomain();?>' ),
    ) );
<?php
            // For Post Types Tab
            $posttype_array = array();

            // Post Types Tab: Loop through Selected Post Types from templates/taxonomies.php
            if ( ( isset( $data['pt'] ) && is_array( $data['pt'] ) ) ) {
                foreach ( $data['pt'] as $key => $posttype ) {
                    // Only Allow Created Post Types
                    if ( array_intersect( $data['pt'], parent::wpPosttypes() ) ) {
                        $posttype_array[$key] = $posttype;
                    }
                }
            }
  
            // Merge Post Type Arrays
            if ( ! empty( $posttype_array ) ) {?>

    // Register <?php echo ucfirst( $plural );?> Taxonomy 
    register_taxonomy( '<?php echo $plural;?>', array( <?php echo implode( ', ', array_map( array( $this, 'addQuotes' ), $posttype_array ) );?> ), $args );
<?php
                // Add Taxonomy To Post Type
                foreach( $posttype_array as $key => $type ) {
                    if ( isset( $key ) ) {?>

    // Add Registered Taxonomy <?php echo ucfirst( $plural );?> To Object Type
    register_taxonomy_for_object_type( 'category', '<?php echo $type;?>' );
    register_taxonomy_for_object_type( 'post_tag', '<?php echo $type;?>' );
<?php                        
                    }
                }
            } else {?>

    // Create <?php echo ucfirst( $plural );?> Taxonomy
    register_taxonomy( '<?php echo parent::sanitizeName( $plural );?>', '', $args );

    // Add Registered Taxonomy <?php echo ucfirst( $plural );?> To Object Type
    register_taxonomy_for_object_type( 'category', '<?php echo parent::sanitizeName( $plural );?>' );
    register_taxonomy_for_object_type( 'post_tag', '<?php echo parent::sanitizeName( $plural );?>' );
<?php }?>
}

add_action( 'init', 'pttmanger_taxonomy_<?php echo parent::sanitizeName( $plural );?>' );



    <?php }


        /**
         * @about Get Theme Text Domain
         * @return string Theme Text Domain
         */
        final private function textdomain()
        {
            $theme = wp_get_theme();
            return $theme->get( 'TextDomain' );
        }


        /**
         * @about Add Quotes Around Array Elements
         * @param string Item to wrap with quotes
         */
        final private function addQuotes( $string )
        {
            return sprintf( "'%s'", $string );
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
