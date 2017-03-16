<?php
/**
 * Plugin Name: Post Type & Taxonomy Manager
 * Plugin URI: https://github.com/tribalNerd/ptt-manager
 * Description: Post Type & Taxonomy Manager for WordPress Themes
 * Tags: post type, post types, custom-post-type, custom post types, taxonomy, taxonomies, custom taxonomy, custom taxonomies
 * Version: 0.1.0
 * License: GNU GPL
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
        'PTT_MANAGER_VERSION'           => '0.1.0',
        'PTT_MANAGER_WP_MIN_VERSION'    => '4.6',

        'PTT_MANAGER_PLUGIN_FILE'       => __FILE__,
        'PTT_MANAGER_PLUGIN_DIR'        => dirname( __FILE__ ),
        'PTT_MANAGER_PLUGIN_BASE'       => plugin_basename( __FILE__ ),

        'PTT_MANAGER_MENU_NAME'         => __( 'PT & T Manager' ),
        'PTT_MANAGER_PAGE_NAME'         => __( 'Post Type & Taxonomy Manager' ),
        'PTT_MANAGER_PAGE_ABOUT'        => __( 'Post Type & Taxonomy Manager For Wordpress Themes' ),
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
        public function init()
        {
            // Activate Plugin
            register_activation_hook( __FILE__, array( $this, 'activate' ) );

            // Deactivate Plugin
            register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

            // Load Admin Area
            add_action( 'wp_loaded', array( 'PTTManager_AdminArea', 'instance' ) );

            // Update Post Type & Taxonomy Options
            add_action( 'init', array( 'PTTManager_Process', 'instance' ) );

            // Register Post Types
            add_action( 'init', array( 'PTTManager_Posttypes', 'instance' ) );

            // Register Taxonomies
            add_action( 'init', array( 'PTTManager_Taxonomies', 'instance' ) );

            // Output PHP For Post Types and Taxonomies
            add_action( 'init', array( 'PTTManager_PHPOutput', 'instance' ) );

            // Inject Plugin Links
            add_filter( 'plugin_row_meta', array( $this, 'links' ), 10, 2 );
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
                wp_die( __( '<b>Activation Failed</b>: The ' . PTT_MANAGER_PAGE_NAME . ' plugin requires WordPress ' . PTT_MANAGER_WP_MIN_VERSION . ' or higher. Please Upgrade Wordpress, then try activating this plugin again.', 'ptt-manager' ) );
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

add_action( 'after_setup_theme', array( 'ptt_manager', 'instance' ) );
