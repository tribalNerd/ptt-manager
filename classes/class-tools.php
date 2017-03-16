<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }

/**
 * @about Register Custom Taxonomies
 * @url http://codex.wordpress.org/Function_Reference/register_taxonomy
 * @url https://codex.wordpress.org/Function_Reference/register_taxonomy_for_object_type
 * @action add_action( 'init', array( 'tNtePTTManager_Taxonomy', 'instance' ) );
 * 
 * @method init()       Init Class Methods
 * @method taxonomies() Start Taxonomy Register
 * @method presets()    Start Presets Register
 * @method register()   Register Taxonomies
 * @method instance()   Class Instance
 */
if( ! class_exists( 'PTTManager_Tools' ) )
{
    class PTTManager_Tools extends PTTManager_Extended
    {
        // Holds Instance Object
        protected static $instance = NULL;


        /**
         * Initiate Registration
         */
        final public function init()
        {
            add_filter( $this->plugin_name . '_get_options', array( $this, 'getOptions' ), 10, 1 );
            
            $this->saveOptions();
        }


        /**
         * Encoded Saved Posttypes
         * @param string $shortname Plugin Name
         * @return string Json Encoded String
         */
        final public function getOptions( $shortname ) {
            $posttype_markers = get_option( $shortname . '_posttype' );

            // Ignore if No Markers
            if ( ! $posttype_markers || empty( $posttype_markers ) ) { return; }

            // Get Saved Post Type Data
            foreach( $posttype_markers as $posttype ) {
                // Get Post Type Records
                $posttype_data[] = get_option( $this->plugin_name . '_posttype_' . $posttype );
            }

            $marged_posttypes = array_merge( $posttype_markers, $posttype_data );

            return esc_html( json_encode( $marged_posttypes) );
        }


        /**
         * Decode Import and Save Options
         * @param string $shortname Plugin Name
         */
        final public function saveOptions() {
            if ( filter_input( INPUT_POST, 'type' ) == "decode" ) {
                // Form Security Check
                parent::validate();

                // Whitelist Setting & Add Temp Option
                register_setting( $this->plugin_name, $this->plugin_name . '_active' );

                // Get Import Post
                $post = filter_input( INPUT_POST, 'import' );

                // Decode The Import
                $decoded = json_decode( trim( $post ), true );

                // Loop/Find Marker Records
                foreach ( $decoded as $key => $posttype ) {
                    if ( ! is_numeric( $key ) ) {
                        $markers[$key] = $posttype;
                    }
                }

                // Update Marker Record
                update_option( $this->plugin_name . '_posttype', $markers, '', false );

                // Loop Through Post Type Records
                foreach ( $decoded as $key => $posttype ) {
                    if ( is_numeric( $key ) ) {
                        // Save Postt Type Record
                        update_option( $this->plugin_name . '_posttype_'. $posttype['singular'], $posttype, '', true );
                    }
                }

                // Display Message
                $this->message( 'importupdate', 'updated' );
            }
        }


        /**
         * Create instance
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
