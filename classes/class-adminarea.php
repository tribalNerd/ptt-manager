<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }


/**
 * @about Admin Area Display
 * @location ptt-manager.php
 * @action PTTManager_AdminArea::instance();
 * 
 * @method init()       Init Admin Actions
 * @method menu()       Load Admin Area Menu
 * @method enqueue()    Load Stylesheets & jQuery
 * @method display()    Include Admin Area Templates
 * @method tabs()       Load Admin Area Tabs
 * @method rules()      Flush Rewrite Rules
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
            add_action( 'admin_menu', array( $this, 'menu' ) );

            // Only If Page Is Plugin Admin Area
            if ( parent::qString( 'page' ) == $this->plugin_name ) {
                add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

                // Flush Rewrite Rules
                $this->rules();
            }
        }


        /**
         * @about Settings Menu
         */
        final public function menu()
        {
            // Website Menu
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
         * @about Enqueue Stylesheet and jQuery
         */
        final public function enqueue()
        {
            // Post Type & Taxonomy Manager
            wp_enqueue_style( $this->plugin_name, plugins_url( '/assets/style.css', $this->plugin_file ), '', date( 'YmdHis', time() ), 'all' );

            // Wordpress Dashicons Picker
            wp_enqueue_style( 'dashicons-picker',  plugins_url( '/vendors/dashicons-picker/css/dashicons-picker.css', $this->plugin_file ), array( 'dashicons' ), '1.0', false );
            wp_enqueue_script( 'dashicons-picker', plugins_url( '/vendors/dashicons-picker/js/dashicons-picker.js', $this->plugin_file ),   array( 'jquery' ), '1.1', true  );
        }


        /**
         * @about Display Admin Templates
         */
        final public function display()
        {
            // Admin Header
            require_once( $this->templates .'/header.php' );

            // Switch Between Tabs
            switch ( parent::qString( 'tab' ) ) {
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
         * @about Admin Area Tabs
         * @return string $html Tab Display
         */
        final public function tabs()
        {
            $html = '<h2 class="nav-tab-wrapper">';

            // Set Current Tab
            $current = ( parent::qString( 'tab' ) ) ? parent::qString( 'tab' ) : key( $this->tabs );

            foreach( $this->tabs as $tab => $name ) {
                // Current Tab Class
                $class = ( $tab == $current ) ? ' nav-tab-active' : '';

                // Tab Links
                $html .= '<a href="?page='. parent::qString( 'page' ) .'&tab='. $tab .'" class="nav-tab'. $class .'">'. $name .'</a>';
            }

            $html .= '</h2><br />';

            return $html;
        }


        /**
         * @about Update Check, Flush Rewrite Rules
         */
        final private function rules()
        {
            // Post Update Action
            if ( get_option( $this->option_name . 'active' ) ) {

                // Flush Rules
                flush_rewrite_rules();

                // Clear Setting
                delete_option( $this->option_name . 'active' );
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
