<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }

/**
 * @about Output PHP For Post Types and Taxonomies
 * @action add_action( 'init', array( 'tNtePTTManager_PHPOutput', 'instance' ) );
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
         */
        final public function display( $type )
        {
            // Post Type Data
            if ( ! empty( $type ) && $type == 'posttypes' ) {
                // Get Post Type Markers
                $types = get_option( $this->plugin_name . '_posttype' );

                // Ignore if No Markers
                if ( ! $types || empty( $types ) ) { return; }

                // Get Saved Post Type Data & Register
                foreach( $types as $type ) {
                    // Get Unique Record Option
                    $data = get_option( $this->plugin_name . '_posttype_' . $type );

                    // Start Registration
                    if ( isset( $data['singular'], $data['plural'] ) ) {
                        $this->posttypes( $this->sanitize( $data['singular'] ), $this->sanitize( $data['plural'] ), $data );
                    }
                }
            } 

            // Taxonomy Data
            if ( ! empty( $type ) && $type == 'taxonomies' ) {
                // Get Post Type Markers
                $taxonomies = get_option( $this->plugin_name . '_taxonomy' );

                // Ignore if No Markers
                if ( ! $taxonomies || empty( $taxonomies ) ) { return; }

                // Get Saved Post Type Data & Register
                foreach( $taxonomies as $taxonomy ) {
                    // Get Unique Record Option
                    $data = get_option( $this->plugin_name . '_taxonomy_' . $taxonomy );

                    // Start Registration
                    if ( isset( $data['singular'], $data['plural'] ) ) {
                        $this->taxonomies( $this->sanitize( $data['singular'] ), $this->sanitize( $data['plural'] ), $data );
                    }
                }
            } 
        }


        /**
         * @about Output Post Types
         */
        final public function posttypes( $single, $plural, $data = array() )
        {
            // Required
            if( ! isset( $single, $plural ) || ! $data || ! is_array( $data ) ) { return; }

            // Add New Dashicons Picker
            if ( isset( $data['dashicons_picker_posttype-add'] ) ) {
                $icon = esc_attr( $data['dashicons_picker_posttype-add'] );

            // Set / Update Icon
            } elseif ( isset( $data['dashicons_picker_posttype-' . $plural] ) ) {
                $icon = esc_attr( $data['dashicons_picker_posttype-' . $plural] );

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
            $restbase = ( $showrest && ! empty( $data['showrest'] ) && ! empty( $data['restbase'] ) ) ? $this->sanitize( $data['restbase'] ) : '""';

            // Archive Slug
            $archiveslug = ( ! empty( $data['archiveslug'] ) ) ? $this->sanitize( $data['archiveslug'] ) : 'false';
            $archive = ( empty( $data['archive'] ) ) ? $archiveslug : 'false';

            // Slug
            $slug = ( ! empty( $data['slug'] ) ) ? $this->sanitizeName( $data['slug'] ) : $this->sanitizeName( $plural );

            // Rewrite Slug
            $rewrite = "array( 'slug' => '" . $slug . "', 'with_front' => 'true' )";

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
            $supports       = array_merge( $author, $comments, $customfields, $editor, $excerpt, $pageattributes, $revisions, $postformats, $thumbnail, $title, $trackbacks );
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
        'capability_type' => 'post',
        'has_archive' => <?php echo $archive;?>,
        'hierarchical' => true,
        'description' => '<?php echo $description;?>',
        'menu_position' => <?php echo $position;?>,
        'menu_icon' => <?php echo $icon;?>,
        'taxonomies' => array( <?php echo implode( ', ', array_map( array( $this, 'addQuotes' ), $taxonomies ) );?> ),
        'supports' => array( <?php echo implode( ', ', array_map( array( $this, 'addQuotes' ), $supports ) );?> ),
        'labels' => array(
            'name' => sprintf( esc_attr_x( '%1$s', 'post type general name', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $plural;?>' ) ),
            'singular_name' => sprintf( esc_attr_x( '%1$s', 'post type singular name', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $single;?>' ) ),
            'menu_name' => sprintf( esc_attr_x( '%1$s', 'admin menu', '<?php echo $this->textdomain();?>' ), '<?php echo $menuname;?>' ),
            'name_admin_bar' => sprintf( esc_attr_x( '%1$s', 'add new on admin bar', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $single;?>' ) ),
            'add_new' => sprintf( esc_attr_x( 'Add %1$s', '<?php echo $single;?>', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $single;?>' ) ),
            'add_new_item' => sprintf( esc_attr__( 'Add New %1$s', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $single;?>' ) ),
            'new_item' => sprintf( esc_attr__( 'New %1$s', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $single;?>' ) ),
            'edit_item' => sprintf( esc_attr__( 'Edit %1$s', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $single;?>' ) ),
            'view_item' => sprintf( esc_attr__( 'View %1$s', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $single;?>' ) ),
            'all_items' => sprintf( esc_attr__( '%1$s', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $plural;?>' ) ),
            'search_items' => sprintf( esc_attr__( 'Search %1$s', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $plural;?>' ) ),
            'parent_item_colon' => sprintf( esc_attr__( 'Parent %1$s:', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $plural;?>' ) ),
            'not_found' => sprintf( esc_attr__( 'No %1$s Found.', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $plural;?>' ) ),
            'not_found_in_trash' => sprintf( esc_attr__( 'No %1$s Found in Trash.', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $plural;?>' ) ),
        ),
    );

    // Register Post Type
    register_post_type( '<?php echo $plural;?>', $args );
}

add_action( 'init', 'pttmanger_posttype_<?php echo $plural;?>' );




<?php }


        /**
         * @about Output Taxonomies
         */
        final public function taxonomies( $single, $plural, $data = array() )
        {
            // Required
            if( ! isset( $single, $plural ) || ! $data || ! is_array( $data ) ) { return; }
 
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
            'name' => sprintf( esc_attr_x( '%1$s', 'taxonomy general name', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $plural;?>' ) ),
            'singular_name' => sprintf(esc_attr_x( '%1$s', 'taxonomy singular name', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $single;?>' ) ),
            'new_item_name' => sprintf( esc_attr__( 'New %1$s', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $single;?>' ) ),
            'edit_item' => sprintf( esc_attr__( 'Edit %1$s', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $plural;?>' ) ),
            'update_item' => sprintf( esc_attr__( 'Update %1$s', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $plural;?>' ) ),
            'add_new_item' => sprintf( esc_attr__( 'Add New %1$s', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $single;?>' ) ),
            'search_items' => sprintf( esc_attr__( 'Search %1$s', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $plural;?>' ) ),
            'all_items' => sprintf( esc_attr__( 'All %1$s', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $plural;?>' ) ),
            'parent_item' => sprintf( esc_attr__( 'Parent %1$s', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $plural;?>' ) ),
            'parent_item_colon' => sprintf( esc_attr__( 'Parent %1$s:', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $plural;?>' ) ),
            'not_found' => sprintf( esc_attr__( 'No %1$s Found', '<?php echo $this->textdomain();?>' ), ucfirst( '<?php echo $plural;?>' ) ),
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
    register_taxonomy( '<?php echo $plural;?>', '', $args );

    // Add Registered Taxonomy <?php echo ucfirst( $plural );?> To Object Type
    register_taxonomy_for_object_type( 'category', '<?php echo $plural;?>' );
    register_taxonomy_for_object_type( 'post_tag', '<?php echo $plural;?>' );
<?php }?>
}

add_action( 'init', 'pttmanger_taxonomy_<?php echo $plural;?>' );



    <?php }


        /**
         * @about Get Theme Text Domain
         * @return type $string The Text Domain
         */
        final private function textdomain()
        {
            $theme = wp_get_theme();
            return $theme->get( 'TextDomain' );
        }


        /**
         * @about Add Quotes Around Array Elements
         * @param type $string Post Type Name
         * @return type $string Quoted String
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
