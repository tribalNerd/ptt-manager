<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }


/**
 * @about Manage Post Types and Taxonomies
 * @action PTTManager_Process::instance();
 * 
 * @method init()           Init Admin Actions
 * @method update()         Start Update/Delete Steps
 * @method deleteOption()   Delete Posttypes & Taxonomies
 * @method deleteSettings() Delete All Saved Settings
 * @method updateBlocker()  Block Plugin From Rendering Post Types / Taxonomies
 * @method removePosttype() Unregister a Post Type
 * @method updatePosttype() Update the Posttype Options
 * @method updateTaxonomy() Update the Taxonomy Options
 * @method cleanup()        Run unset() on $_POST
 * @method dashiconName()   Rebuild the Dashicon Name for New Records
 * @method dashiconString() Output the Dashicon String
 * @method import()         Import, Decode Post and Save Options
 * @method export()         Export Posttypes & Taxonomies
 * @method instance()       Class Instance
 */
if ( ! class_exists( 'PTTManager_Process' ) )
{
    class PTTManager_Process extends PTTManager_Extended
    {
        // Holds Instance Object
        protected static $instance = NULL;


        /**
         * @about Init Admin Actions
         */
        final public function init()
        {
            // Plugin Admin Only
            if ( filter_input( INPUT_POST, 'type' ) && parent::qString( 'page' ) == $this->plugin_name ) {
                // Post Action is Active: Marker For Flush Rewrite Rules
                update_option( $this->option_name . 'active', '1', 'no' );

                // Update Settings
                add_action( 'admin_init', array( $this, 'update') );

                // Edit Post Types / Taxonomies Dropdown
                add_filter( $this->plugin_name . '_dashicon', array( $this, 'dashiconString' ), 10, 1 );

                // Export Filter
                add_filter( $this->plugin_name . '_export', array( $this, 'export' ), 10, 1 );

                // Saved Settings
                add_action( $this->plugin_name . '_settings', array( $this, 'settings' ) );
            }
        }


        /**
         * @about Start Options Update
         */
        final public function update()
        {
            // Form Security Check
            parent::validate();

            // Delete Option
            if ( filter_input( INPUT_POST, 'delete' ) == "1" ) {
                // Get Post Data
                $post = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );

                // Delete Option
                $this->deleteOption( $post['type'], $post );

            // Delete All Saved Settings
            } elseif ( filter_input( INPUT_POST, 'type' ) == "deletesettings" ) {
                // Get Post Data
                $post = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );

                // Delete Settings
                $this->deleteSettings( $post );

            // Update Post Types
            } elseif ( filter_input( INPUT_POST, 'type' ) == "posttype_block" || filter_input( INPUT_POST, 'type' ) == "taxonomy_block" ) {
                // Get Post Data
                $post = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );

                // Update Option
                $this->updateBlocker( $post );

            // Update Post Types
            } elseif ( filter_input( INPUT_POST, 'type' ) == "posttype" && filter_input( INPUT_POST, 'delete' ) != "1" ) {
                // Get Post Data
                $post = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );

                // Rebuild Dashicons Name for New Records
                $data = $this->dashiconName( $post );

                // Update Option
                $this->updatePosttype( $data );

            // Update Taxonomies
            } elseif ( filter_input( INPUT_POST, 'type' ) == "taxonomy" && filter_input( INPUT_POST, 'delete' ) != "1" ) {
                // Get Post Data
                $post = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );

                // Update Option
                $this->updateTaxonomy( $post );

            // Import Posttypes / Taxonomies
            } elseif ( filter_input( INPUT_POST, 'type' ) == "import" ) {
                // Get Post Types Import
                if ( filter_input( INPUT_POST, 'posttypes' ) ) {
                    $post = filter_input( INPUT_POST, 'posttypes' );
                }

                // Get Taxonomies Import
                if ( filter_input( INPUT_POST, 'taxonomies' ) ) {
                   $post = filter_input( INPUT_POST, 'taxonomies' );
                }

                // Process Import
                if ( ! $post ) {
                    $this->import( $post );
                } else {
                    // Display Message
                    parent::message( 'importerror', 'error' );
                }
            }
        }


        /**
         * @about Delete Option(s)
         * @param string $type Hidden input with record type
         * @param array $data $_POST data array
         */
        final private function deleteOption( $type, $data = array() )
        {
            // Verify Data Exists
            if ( ! $data || ! isset( $type ) || ! isset( $data['plural'] ) ) {
                // Display Message
                parent::message( $type . 'error', 'error' );

                return;
            }

            // Sanitize Plural Name
            if ( $type == "posttype" || $type == "taxonomy" ) {
                $name = parent::sanitizeName( $data['plural'] );
            }

            // Unregister Post Type
            if ( $type == "posttype" ) {
                $this->removePosttype( $name );
            }

            // Whitelist The Setting & Add Temp Option
            if ( isset( $name ) ) {

                // Delete Post Type / Taxonomy Record
                delete_option( $this->option_name . $type . '_' . $name );

                // Get Post Type / Taxonomy Array
                $option = get_option( $this->option_name . $type );

                // Remove Record
                unset( $option[$name] );

                if ( $option && is_array( $option ) ) {
                    // Re-update Option
                    update_option( $this->option_name . $type, $option, '', false );
                } else {
                    // Delete Post Type / Taxonomy Record
                    delete_option( $this->option_name . $type );
                }

                // Display Message
                parent::message( $type . 'update', 'updated' );

            // Error No Name Set
            } else {
                // Display Message
                parent::message( $type . 'error', 'error' );
            }
        }


        /**
         * @about Delete All Saved Settings
         */
        final private function deleteSettings()
        {
            // Delete Settings
            foreach( wp_load_alloptions() as $option => $value ) {
                if ( strpos( $option, $this->plugin_name ) === 0 ) {
                    delete_option( $option );
                }
            }

            // Display Message
            parent::message( 'settingsupdate', 'updated' );
        }


        /**
         * @about Block Plugin From Rendering Post Types / Taxonomies
         * @param array $data $_POST data array
         */
        final private function updateBlocker( $data = array() )
        {
            // Unset Values That Are Not Needed
            $post = $this->cleanup( '', $data );

            // Remove Option If Not Checked
            if ( ! $post && isset( $data['type'] ) && $data['type'] == "posttype_block" ) {
                delete_option( $this->option_name . 'posttype_block' );

            // Else Add Option
            } elseif ( $post && isset( $data['type'] ) && $data['type'] == "posttype_block" ) {
                update_option( $this->option_name . 'posttype_block', $post, '', true );
            }

            // Remove Option If Not Checked
            if ( ! $post && isset( $data['type'] ) && $data['type'] == "taxonomy_block" ) {
                delete_option( $this->option_name . 'taxonomy_block' );

            // Else Add Option
            } elseif ( $post && isset( $data['type'] ) && $data['type'] == "taxonomy_block" ) {
                update_option( $this->option_name . 'taxonomy_block', $post, '', true );
            }

            // Display Message
            parent::message( 'blockerupdate', 'updated' );
        }


        /**
         * @about Unregister a Post Type
         * @param string $posttype Post Type Name
         */
        final private function removePosttype( $posttype = '' ) {
            // Required
            if ( empty( $posttype ) ) { return; }

            // Unregister The Post Type
            unregister_post_type( $posttype );
        }


        /**
         * @about Update Posttypes or Remove if Array is Empty
         * @param array $data $_POST data array
         */
        final private function updatePosttype( $data = array() )
        {
            // Verify Data Exists
            if ( ! $data || ! isset( $data['plural'] ) ) {
                // Display Message
                parent::message( 'posttypeerror', 'error' );
                return;
            }

            // Make Sure Post Type Isn't Already Active
            if ( ! parent::isActive( 'posttype', $data ) ) {
                parent::message( 'duplicate', 'error' );
                return;
            }
            
            // Set Post Type Name & Sanitize
            $name = parent::sanitizeName( $data['plural'] );

            // Unset Unused $_POST Data
            $post = $this->cleanup( $name, $data );

            // Update Option
            if ( $post ) {
                // Get Array Record With Saved Types
                $option_array = get_option( $this->option_name . 'posttype' );

                // Remove Old Record If Duplicate
                if ( isset( $option_array[$name] ) ) {
                    unset( $option_array[$name] );
                }

                // Other Records Found, Rebuild Marker Array Record
                if ( $option_array && is_array( $option_array ) ) {
                    // Merge Arrays
                    $new_option_array = array_merge( array( $name => $name ), $option_array );

                    // Update Array Record
                    update_option( $this->option_name . 'posttype', $new_option_array, '', false );

                // New Array Record
                } else {
                    update_option( $this->option_name . 'posttype', array( $name => $name ), '', false );
                }

                // Save Posttype Record
                update_option( $this->option_name . 'posttype_'. $name, $post, '', true );

                // Display Message
                parent::message( 'posttypeupdate', 'updated' );

            // Error, Post is Empty
            } else {
                // Display Message
                parent::message( 'posttypeerror', 'error' );
            }
        }


        /**
         * @about Update Taxonomies or Remove if Array is Empty
         * @param array $data $_POST data array
         */
        final private function updateTaxonomy( $data = array() )
        {
            // Verify Data Exists
            if ( ! $data || ! isset( $data['slug'] ) ) {
                // Display Message
                parent::message( 'taxonomyerror', 'error' );
                return;
            }

            // Make Sure Post Type Isn't Already Active
            if ( ! parent::isActive( 'taxonomy', $data ) ) {
                parent::message( 'duplicate', 'error' );
                return;
            }
            
            // Set Post Type Name & Sanitize
            $name = parent::sanitizeName( $data['plural'] );

            // Unset: Clean Unused $_POST Data
            $post = $this->cleanup( '', $data );

            // Update Name Marker Option & Full Taxonomy Record Option
            if ( $post ) {
                // Get Array Record With Saved Types
                $option_array = get_option( $this->option_name . 'taxonomy' );

                // Remove Old Record If Duplicate
                if ( isset( $option_array[$name] ) ) {
                    unset( $option_array[$name] );
                }

                // Other Records Found, Rebuild Array Record
                if ( ! empty( $option_array ) && is_array( $option_array ) ) {
                    // Merge Arrays
                    $new_option_array = array_merge( array( $name => $name ), $option_array );

                    // Update Array Record
                    update_option( $this->option_name . 'taxonomy', $new_option_array, '', false );

                // New Array Record
                } else {
                    update_option( $this->option_name . 'taxonomy', array( $name => $name ), '', false );
                }

                // Add Default Post Type Support If Not Set
                if ( ! isset( $post['pt'] ) ) {
                    $post = array_merge( array( 'pt' => array( 'page' => 'page' ) ), $post );
                }

                // Save Taxonomy Record
                update_option( $this->option_name . 'taxonomy_'. $name, $post, '', true );

                // Display Message
                parent::message( 'taxonomyupdate', 'updated' );

            // Error, Post is Empty
            } else {
                // Display Message
                parent::message( 'taxonomyerror', 'error' );
            }
        }


        /**
         * @about Unset Values from $_POST
         * @param array $single Single Name For Icon Clean
         * @param array $post $_Post Data
         * @return array $post Cleaned Record
         */
        final private function cleanup( $name, $post = array() )
        {
            if ( ! empty( $post ) && is_array( $post ) ) {
                // Remove Unused Values
                unset( $post['ptt-manager_nonce'] );
                unset( $post['_wp_http_referer'] );
                unset( $post['option_page'] );
                unset( $post['action'] );
                unset( $post['submit'] );
                unset( $post['type'] );

                // Remove All Empty Fields
                foreach ( $post as $name => $field ) {
                    if ( empty( $field ) || ! isset( $field ) ) { unset( $post[$name] ); }
                    if ( isset( $field ) && $field == 'dashicons-' ) { unset( $post[$name] ); }
                }

                return $post;
            }
        }


        /**
         * @about Rebuild the Dashicon Name for New Records
         * @param array $post The $_POST Array
         * @return array $data Rebuilt/Original $_POST Array
         */
        final public function dashiconName( $post )
        {
            // Rebuild Dashicon If New Record, then Rebuild $data Array
            if ( isset( $post['plural'], $post['dashicons_picker_add'] ) ) {

                // Rebuild Icon Array
                $icon_data = array( 'dashicons_picker_' . parent::sanitizeName( $post['plural'] ) => $post['dashicons_picker_add'] );

                // Unset Add Icon Marker
                unset( $post['dashicons_picker_add'] );

                // Rebuilt $data Array
                $data = array_merge( $icon_data, $post );
            } else {
                $data = $post;
            }

            return $data;
        }


        /**
         * @about Output the Dashicon String
         * @location templates/posttypes.php
         * @call apply_filters( $this->plugin_name . '_dashicon', 'dashicons_picker_' );
         */
        final public function dashiconString( $string )
        {
            return ( parent::qString( 'posttype' ) ) ? $string . esc_attr( parent::qString( 'posttype' ) ) : $string . 'add';
        }


        /**
         * @about Import, Decode Post and Save Options
         * @param string $post Import Data
         */
        final public function import( $post = '' )
        {
            // Verify Post Exists
            if ( empty( $post ) ) {
                // Display Message
                parent::message( 'importerror', 'error' );

                return;
            }

            // Post Type Import
            if ( filter_input( INPUT_POST, 'posttypes' ) ) {
                $type = 'posttype';

            // Taxonomy Import
            } elseif ( filter_input( INPUT_POST, 'taxonomies' ) ) {
                $type = 'taxonomy';
            }

            // Decode The Import
            $decoded = json_decode( trim( $post ), true );

            if ( isset( $type, $decoded ) ) {
                // Loop/Find Marker Records
                foreach ( $decoded as $key => $data ) {
                    if ( ! is_numeric( $key ) ) {
                        $markers[$this->sanitizeName( $key )] = $data;
                    }
                }

                // Update Marker Record
                update_option( $this->option_name . $this->sanitizeName( $type ), $markers, '', false );

                // Loop Through Records
                foreach ( $decoded as $key => $data ) {
                    if ( is_numeric( $key ) ) {
                        $data = filter_var_array( $data, FILTER_SANITIZE_STRING );

                        // Save Postt Type Record
                        update_option( $this->option_name . $this->sanitizeName( $type ) . '_' . $this->sanitizeName( $data['plural'] ), $data, '', true );
                    }
                }

                // Display Message
                $this->message( 'importupdate', 'updated' );
            }
        }


        /**
         * @about Export Saved Posttypes & Taxonomies
         * @param string $type Posttype or Taxonomy
         * @call apply_filters( $this->plugin_name . '_export', 'posttype' );
         * @call apply_filters( $this->plugin_name . '_export', 'taxonomy' );
         * @echo string JSON Encoded Data
         */
        final public function export( $type = '' )
        {
            // Required
            if ( empty( $type ) ) { return; }

            // Get Saved Option Markers
            $markers = ( get_option( $this->option_name . $type ) ) ? get_option( $this->option_name . $type ) : '';

            // Ignore if No Markers
            if ( ! empty( $markers ) ) {
                // Get Saved Post Type Data
                foreach( $markers as $record ) {
                    // Get Records
                    $data[] = get_option( $this->option_name . $this->sanitizeName( $type ) . '_' . $this->sanitizeName( $record ) );
                }

                // Merge Makers and Records
                $merged_data = array_merge( $markers, $data );

                // Export Encoded JSON Object
                echo json_encode( $merged_data );
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
