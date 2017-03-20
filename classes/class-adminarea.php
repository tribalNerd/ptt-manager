<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }


/**
 * @about Admin Area Display
 * @action add_action( 'wp_loaded', array( 'PTTManager_AdminArea', 'instance' ) );
 * 
 * @method init()       Init Admin Actions
 * @method display()    Include Admin Area Templates
 * @method rules()      Flush Rewrite Rules
 * @method enqueue()    Load Stylesheets & jQuery
 * @method menu()       Load Admin Area Menu
 * @method tabs()       Load Admin Area Tabs
 * @method instance()   Class Instance
 */
if ( ! class_exists( 'PTTManager_AdminArea' ) )
{
    class PTTManager_AdminArea extends PTTManager_Extended
    {
        // Holds Instance Object
        protected static $instance = NULL;


        /**
         * @about Init Admin Actions
         */
        final public function init()
        {
            // Website Menu Link
            add_action( 'admin_menu', array( &$this, 'menu' ) );

            // Only If Page Is Plugin Admin Area
            if ( filter_input( INPUT_GET, 'page' ) == $this->plugin_name ) {
                // User Level Protection
                parent::protect();

                // Add Scripts
                add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue' ) );
            }
        }


        /**
         * @about Display Admin Templates
         */
        final public function display()
        {
            // Flush Rewrite Rules
            $this->rules();

            // Admin Header
            require_once( $this->templates .'/header.php' );

            // Switch Between Tabs
            switch ( filter_input( INPUT_GET, 'tab' ) ) {
                case 'home':
                default:
                    require_once( $this->templates .'/home.php' );
                break;

                case 'importexport':
                    require_once( $this->templates .'/importexport.php' );
                break;

                case 'posttypes':
                    require_once( $this->templates .'/posttypes.php' );
                break;

                case 'phpoutput':
                    require_once( $this->templates .'/phpoutput.php' );
                break;

                case 'settings':
                    require_once( $this->templates .'/settings.php' );
                break;

                case 'taxonomies':
                    require_once( $this->templates .'/taxonomies.php' );
                break;

                case 'templates':
                    require_once( $this->templates .'/templates.php' );
                break;
            }

            // Admin Footer
            require_once( $this->templates .'/footer.php' );
        }


        /**
         * @about Update Check, Flush Rewrite Rules
         */
        final private function rules()
        {
            // Created by register_setting()
            if ( get_option( PTT_MANAGER_PLUGIN_NAME . '_active' ) !== false ) {
                // Remove Temp Option Set With register_setting() 
                unregister_setting( $this->plugin_name, $this->plugin_name );

                // Flush Rules
                flush_rewrite_rules();

                // Clear Setting
                delete_option( $this->plugin_name . '_active' );
            }
        }


        /**
         * @about Enqueue Stylesheet and jQuery
         */
        final public function enqueue()
        {
            // Post Type & Taxonomy Manager
            wp_enqueue_style( $this->plugin_name, plugins_url( '/assets/css/style.css', $this->plugin_file ), '', date( 'YmdHis', time() ), 'all' );

            // Wordpress Dashicons Picker
            wp_enqueue_style( 'dashicons-picker',  plugins_url( '/vendors/dashicons-picker/css/dashicons-picker.css', $this->plugin_file ), array( 'dashicons' ), '1.0', false );
            wp_enqueue_script( 'dashicons-picker', plugins_url( '/vendors/dashicons-picker/js/dashicons-picker.js', $this->plugin_file ),   array( 'jquery' ), '1.1', true  );
        }


        /**
         * @about Settings Menu
         */
        final public function menu()
        {
            // Logged in users only
            if( ! is_user_logged_in() && ! is_user_member_of_blog() ) { return; }

            // Settings Menu
            add_submenu_page(
                'options-general.php',
                $this->plugin_title,
                $this->menu_name,
                'manage_options',
                $this->plugin_name,
                array( $this, 'display' )
            );
        }


        /**
         * @about Admin Area Tabs
         * @return string $html Tab Display
         */
        final public function tabs()
        {
            if( filter_input( INPUT_GET, "page" ) && ! empty( $this->tabs ) && is_array( $this->tabs ) ) {
                // Set current tab
                $current = ( filter_input( INPUT_GET, "tab" ) ) ? parent::filterInputGet( 'tab' ) : key( $this->tabs );

                // Tabs html
                $html = '<h2 class="nav-tab-wrapper">';
                    foreach( $this->tabs as $tab => $name ) {
                        // Current tab class
                        $class = ( $tab == $current ) ? ' nav-tab-active' : '';

                        // Tab links
                        $html .= '<a href="?page='. parent::filterInputGet( 'page' ) .'&tab='. $tab .'" class="nav-tab'. $class .'">'. $name .'</a>';
                    }
                $html .= '</h2><br />';

                return $html;
            }
        }


        /**
         * Create Instance
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
