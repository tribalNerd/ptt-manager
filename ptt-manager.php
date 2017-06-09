<?php
/**
 * Plugin Name: Post Type & Taxonomy Manager | PT & T Manager
 * Plugin URI: https://github.com/tribalNerd/ptt-manager
 * Description: Post Type & Taxonomy Manager for WordPress Themes
 * Tags: post type, post types, custom-post-type, custom post types, taxonomy, taxonomies, custom taxonomy, custom taxonomies
 * Version: 0.1.3
 * License: GNU GPLv3
 * Copyright (c) 2017 Chris Winters
 * Author: tribalNerd, Chris Winters
 * Author URI: http://techNerdia.com/
 * Text Domain: ptt-manager
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }


/**
 * @about Define Constants
 */
if( function_exists( 'PTTManagerConstants' ) )
{
    PTTManagerConstants( Array(
        'PTT_MANAGER_BASE_URL'          => get_bloginfo( 'url' ),
        'PTT_MANAGER_VERSION'           => '0.1.3',
        'PTT_MANAGER_WP_MIN_VERSION'    => '4.6',

        'PTT_MANAGER_PLUGIN_FILE'       => __FILE__,
        'PTT_MANAGER_PLUGIN_DIR'        => dirname( __FILE__ ),
        'PTT_MANAGER_PLUGIN_BASE'       => plugin_basename( __FILE__ ),

        'PTT_MANAGER_MENU_NAME'         => __( 'PT & T Manager', 'ptt-manager' ),
        'PTT_MANAGER_PAGE_NAME'         => __( 'Post Type & Taxonomy Manager', 'ptt-manager' ),
        'PTT_MANAGER_PAGE_DESC'         => __( 'Post Type & Taxonomy Manager For WordPress Themes', 'ptt-manager' ),
        'PTT_MANAGER_OPTION_NAME'       => 'ptt-manager_',
        'PTT_MANAGER_PLUGIN_NAME'       => 'ptt-manager',

        'PTT_MANAGER_CLASSES'           => dirname( __FILE__ ) .'/classes',
        'PTT_MANAGER_TEMPLATES'         => dirname( __FILE__ ) .'/templates'
    ) );
}


/**
 * @about Loop Through Constants
 */
function PTTManagerConstants( $constants_array )
{
    foreach( $constants_array as $name => $value ) {
        define( $name, $value, true );
    }
}


/**
 * @about Register Classes & Include
 */
spl_autoload_register( function ( $class )
{
    if( strpos( $class, 'PTTManager_' ) !== false ) {
        $class_name = str_replace( 'PTTManager_', "", $class );

        // If the Class Exists, Include the Class
        if( file_exists( PTT_MANAGER_CLASSES .'/class-'. strtolower( $class_name ) .'.php' ) ) {
            include_once( PTT_MANAGER_CLASSES .'/class-'. strtolower( $class_name ) .'.php' );
        }
    }
} );


/**
 * @about Run Plugin
 */
if( ! class_exists( 'ptt_manager' ) )
{
    class ptt_manager
    {
        // Holds Instance Object
        protected static $instance = NULL;


        /**
         * @about Initiate Plugin
         */
        final public function init()
        {
            // Activate Plugin
            register_activation_hook( __FILE__, array( $this, 'activate' ) );

            // Deactivate Plugin
            register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

            // Inject Plugin Links
            add_filter( 'plugin_row_meta', array( $this, 'links' ), 10, 2 );

            // Load Admin Area
            PTTManager_AdminArea::instance();

            // Update Settings
            PTTManager_Process::instance();

            // Output PHP For Post Types and Taxonomies
            if ( filter_input( INPUT_GET, 'tab' ) == 'phpoutput' ) {
                PTTManager_PHPOutput::instance();
            }

            // Start Post Type Register
            $this->posttypes();

            // Start Taxonomy Register
            $this->taxonomies();
        }


        /**
         * @about Start Post Type Register
         */
        final private function posttypes()
        {
            // Stop Registration if Block is Enabled
            if ( get_option( PTT_MANAGER_OPTION_NAME . 'posttype_block' ) ) { return; }

            // Get Post Type Markers
            $markers = get_option( PTT_MANAGER_OPTION_NAME . 'posttype' );

            // Markers Required
            if ( $markers && ! empty( $markers ) && is_array( $markers ) ) {
                // Create Post Type for each Marker
                foreach( $markers as $marker ) {
                    // Get Post Type Option
                    $option_data = get_option( PTT_MANAGER_OPTION_NAME . 'posttype_' . $marker );

                    // Create Post Type
                    new PTTManager_Posttypes( $option_data );
                }
            }
        }


        /**
         * @about Start Taxonomy Register
         */
        final private function taxonomies()
        {
            // Stop Registration if Block is Enabled
            if ( get_option( PTT_MANAGER_OPTION_NAME . 'taxonomy_block' ) ) { return; }

            // Get Taxonomy Markers
            $taxonomy_markers = get_option( PTT_MANAGER_OPTION_NAME . 'taxonomy' );

            // Markers Required
            if ( $taxonomy_markers && ! empty( $taxonomy_markers ) && is_array( $taxonomy_markers ) ) {
                // Get Saved Taxonomy Data & Register
                foreach( $taxonomy_markers as $taxonomy_marker ) {
                    // Get Taxonomy Option
                    $taxonomy_data = get_option( PTT_MANAGER_OPTION_NAME . 'taxonomy_' . $taxonomy_marker );

                    // Create Taxonomy
                    new PTTManager_Taxonomies( $taxonomy_data );
                }
            }
        }


        /**
         * @about Activate Plugin
         */
        final public function activate()
        {
            // Wordpress Version Check
            global $wp_version;

            // Version Check
            if( version_compare( $wp_version, PTT_MANAGER_WP_MIN_VERSION, "<" ) ) {
                wp_die( __( '<b>Activation Failed</b>: The ' . PTT_MANAGER_PAGE_NAME . ' plugin requires WordPress version ' . PTT_MANAGER_WP_MIN_VERSION . ' or higher. Please Upgrade Wordpress, then try activating this plugin again.', 'ptt-manager' ) );
            }

            // Flush Rewrite Rules
            add_action( 'after_switch_theme', 'flush_rewrite_rules' );
        }


        /**
         * @about Deactivate Plugin
         */
        final public function deactivate()
        {
            // Clear Plugin Permalinks
            flush_rewrite_rules();
        }


        /**
         * @about Inject Links Into Plugin Admin
         * @param array $links Default links for this plugin
         * @param string $file The name of the plugin being displayed
         * @return array $links The links to inject
         */
        final public function links( $links, $file )
        {
            // Get Current URL
            $request_uri = filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL );

            // Only this plugin and on plugins.php page
            if ( $file == PTT_MANAGER_PLUGIN_BASE && strpos( $request_uri, "plugins.php" ) !== false ) {
                // Links To Inject
                $links[] = '<a href="options-general.php?page=' . PTT_MANAGER_PLUGIN_NAME . '">'. __( 'Settings', 'ptt-manager' ) .'</a>';
                $links[] = '<a href="http://technerdia.com/tnte/ptt_manager/#faq" target="_blank">'. __( 'F.A.Q.', 'ptt-manager' ) .'</a>';
                $links[] = '<a href="http://technerdia.com/help/" target="_blank">'. __( 'Support', 'ptt-manager' ) .'</a>';
                $links[] = '<a href="http://technerdia.com/feedback/" target="_blank">'. __( 'Feedback', 'ptt-manager' ) .'</a>';
                $links[] = '<a href="http://technerdia.com/donate/" target="_blank">'. __( 'Donations', 'ptt-manager' ) .'</a>';
                $links[] = '<a href="http://technerdia.com/tnte/ptt_manager/" target="_blank">'. __( 'Plugin Home', 'ptt-manager' ) .'</a>';
            }

            return $links;
        }


        /**
        * @about Create Instance
        */
        final public static function instance()
        {
            if ( ! self::$instance ) {
                self::$instance = new self();
                self::$instance->init();
            }

            return self::$instance;
        }
    }
}

add_action( 'after_setup_theme', array( 'ptt_manager', 'instance' ), 0 );
