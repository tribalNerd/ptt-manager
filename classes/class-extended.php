<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }

/**
 * @about Core PPT Manager Class
 * 
 * @method __construct()    Set Parent Variables
 * @method message()        Display Messages To User
 * @method isActive()       Post Type / Taxonomy Active Check
 * @method presetPosttype() Preset Post Type Fields
 * @method presetTaxonomy() Preset Taxonomy Fields
 * @method editDropdown()   Get Saved Options For Edit Dropdown
 * @method sanitize()       Sanitize Text Strings
 * @method sanitizeName()   Sanitize Label/Name
 * @method wpPosttypes()    Get All Post Type Keys
 * @method createdUpdated() Created/Updated Inputs and Message
 * @method listPosttypes()  Create Checkbox Listing of Post Types
 * @method listTaxonomies() Checkbox Listing of Taxonomies
 * @method date()           Get the Current Date & Time
 * @method author()         Get Current Logged In User Display Name
 * @method settings()       Get All Plugin Options
 * @method field()          Get Saved Option Data For Inputs/Display
 * @method qString()        Get Query String Item
 * @method validate()       Form Validation
 */
if( ! class_exists( 'PTTManager_Extended' ) )
{
    class PTTManager_Extended
    {
        // Website URL
        public $base_url;

        // The plugin-slug-name
        public $plugin_name;
        
        // Plugin Page Title
        public $plugin_title;
        
        // Plugin Page Description
        public $plugin_desc;
        
        // Plugin filename.php
        public $plugin_file;
        
        // Current Plugin Version
        public $plugin_version;
        
        // Plugin Menu Name
        public $menu_name;
        
        // Path To Plugin Templates
        public $templates;

        // Base Option Name
        public $option_name;

        // True if Plugin Options
        public $settings = false;


        /**
         * @about Set Class Vars
         */
        function __construct()
        {
            // Set Vars
            $this->base_url         = PTT_MANAGER_BASE_URL;
            $this->plugin_name      = PTT_MANAGER_PLUGIN_NAME;
            $this->plugin_title     = PTT_MANAGER_PAGE_NAME;
            $this->plugin_desc      = PTT_MANAGER_PAGE_DESC;
            $this->plugin_file      = PTT_MANAGER_PLUGIN_FILE;
            $this->plugin_version   = PTT_MANAGER_VERSION;
            $this->menu_name        = PTT_MANAGER_MENU_NAME;
            $this->option_name      = PTT_MANAGER_OPTION_NAME;
            $this->templates        = PTT_MANAGER_TEMPLATES;
        }


        /**
         * @about Display Messages To User
         * @param string $slug Which switch to load
         * @param string $notice_type Either updated/error
         */
        final public function message( $slug, $notice_type = false )
        {
            // Clear Message
            $message = '';

            // Message Switch
            switch ( $slug ) {
                case 'posttypeupdate':
                    $message = __( 'Post Type Settings Updated! You may need to <a href="javascript:window.location.reload(true)">refresh</a> to see the post type within the admin menu.', 'ptt-manager' );
                break;

                case 'taxonomyupdate':
                    $message = __( 'Taxonomy Settings Updated! You may need to <a href="javascript:window.location.reload(true)">refresh</a> to see the taxonomy within the admin menu.', 'ptt-manager' );
                break;

                case 'blockerupdate':
                    $message = __( 'Blocker Settings Updated!', 'ptt-manager' );
                break;

                case 'importupdate':
                    $message = __( 'Settings Imported!', 'ptt-manager' );
                break;

                case 'settingsupdate':
                    $message = __( 'Settings Deleted!', 'ptt-manager' );
                break;

                case 'duplicate':
                    $message = __( 'The Post Type / Taxonomy Is Already Registered or Reserved!', 'ptt-manager' );
                break;

                case 'posttypeerror':
                    $message = __( 'Post Type Settings Failed To Update!', 'ptt-manager' );
                break;

                case 'taxonomyerror':
                    $message = __( 'Taxonomy Settings Failed To Update!', 'ptt-manager' );
                break;

                case 'blockererror':
                    $message = __( 'Blocker Settings Failed To Update!', 'ptt-manager' );
                break;

                case 'importerror':
                    $message = __( 'Import Failed!', 'ptt-manager' );
                break;

                case 'unknownerror':
                    $message = __( 'Error: No Post Data!', 'ptt-manager' );
                break;

            }

            // Throw Message
            if ( ! empty( $message ) ) {
                // Set Message Type, Default Error
                $type = ( $notice_type == "updated" ) ? "updated" : "error";

                // Return Message
                add_settings_error( $slug, $slug, $message, $type );
            }
        }


        /**
         * @about Make sure the post type / taxonomy isn't active already
         * @return bool $return True if Post Type / Taxonomy is not active
         */
        final public function isActive( $type, $data = array() )
        {
            // Clean Return
            $return = (bool) true;

            // Required
            if ( ! isset( $type ) || ! $data || $data && ! is_array( $data ) ) { $return = (bool) false; }

            // Reserved Names
            $reserved = array(
                'post',
                'page',
                'attachment',
                'revision',
                'nav_menu_item',
                'custom_css',
                'customize_changeset',
                'action',
                'author',
                'order',
                'theme'
            );

            // Check Post Type
            if ( $return && $type == "posttype" ) {
                // Name is Reserved
                if ( in_array( $this->sanitizeName( $data['plural'] ), $reserved ) ) {
                    $return = (bool) false;
                }

                // If Post Type Exists
                if ( ! isset( $data['updated'] ) && get_post_type_object( $this->sanitizeName( $data['plural'] ) ) ) {
                    $return = (bool) false;
                }
            }

            // Check Taxonomy
            if ( $return && $type == "taxonomy" ) {
                // Name is Reserved
                if ( in_array( $this->sanitizeName( $data['plural'] ), $reserved ) ) {
                    $return = (bool) false;
                }

                // If Taxonomy Exists
                if ( ! isset( $data['updated'] ) && get_taxonomy( $this->sanitizeName( $data['plural'] ) ) ) {
                    $return = (bool) false;
                }
            }

            return ( false === $return ) ? false : true;
        }


        /**
         * @about Display Preset Post Type Fields
         * @param string $plural    Plural Post Type Name
         * @param string $single    Single Post Type Name
         * @param string $about     About Preset Post Type
         */
        final public function presetPosttype( $plural, $singular, $about )
        {
            if ( ! get_option( $this->option_name . 'posttype_' . $plural ) ) {?>
                <form enctype="multipart/form-data" method="post" action="">
                <?php wp_nonce_field( $this->option_name . 'action', $this->option_name . 'nonce' );?>
                <input type="hidden" name="type" value="posttype" />
                <input type="hidden" name="plural" value="<?php echo esc_attr( $plural );?>" />
                <input type="hidden" name="singular" value="<?php echo esc_attr( $singular );?>" />
                <input type="hidden" name="created" value="<?php echo esc_attr( $this->date() );?>" />
                <input type="hidden" name="author" value="<?php echo esc_attr( $this->author() );?>" />
                <input id="dashicons_picker_<?php echo esc_attr( $plural );?>" name="dashicons_picker_<?php echo esc_attr( $plural );?>" type="hidden" value="" />
                    <tr>
                        <td><label for="<?php echo esc_attr( $plural );?>"><b><?php echo ucfirst( $plural );?>:</b> <?php echo $about;?></label></th>
                        <td class="td10 textcenter"><input class="button dashicons-picker" type="button" value="Icon" data-target="#dashicons_picker_<?php echo esc_attr( $plural );?>" /></td>
                        <td class="td10 textcenter"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Activate', 'ptt-manager' );?>"></td>
                    </tr>
                </form>
            <?php }

            if ( get_option( $this->option_name . 'posttype_' . $plural ) ) {
                // Get Taxonomy Data
                if ( get_option( $this->option_name . 'posttype_' . $plural ) ) {
                    $data = get_option( $this->option_name . 'posttype_' . $plural );
                }

                // Dashicon
                $dashicon = ( $data && isset( $data['dashicons_picker_' . $plural] ) ) ? $data['dashicons_picker_' . $plural] : '';
                ?>
                    <tr>
                        <td><label for="<?php echo esc_attr( $plural );?>"><b><?php echo ucfirst( $plural );?>:</b> <?php echo $about;?></label></th>
                        <td class="td10 textcenter"><span class="dashicons <?php echo $dashicon;?>"></span></td>
                        <td class="td10 textcenter"><a href="<?php echo admin_url();?>options-general.php?page=ptt-manager&tab=posttypes&posttype=<?php echo esc_attr( $plural );?>" class="button"><?php _e( 'Modify', 'ptt-manager' );?></a></td>
                    </tr>
            <?php }
        }


        /**
         * @about Display Preset Taxonomy Fields
         * @param string $plural    Plural Post Type Name
         * @param string $single    Single Post Type Name
         * @param string $about     About Preset Taxonomy
         */

        final public function presetTaxonomy( $plural, $singular, $about )
        {
            if ( ! get_option( $this->option_name . 'taxonomy_' . $plural ) ) {?>
                <form enctype="multipart/form-data" method="post" action="">
                <?php wp_nonce_field( $this->option_name . 'action', $this->option_name . 'nonce' );?>
                <input type="hidden" name="type" value="taxonomy" />
                <input type="hidden" name="plural" value="<?php echo esc_attr( $plural );?>" />
                <input type="hidden" name="singular" value="<?php echo esc_attr( $singular );?>" />
                <input type="hidden" name="slug" value="<?php echo esc_attr( $plural );?>" />
                <input type="hidden" name="created" value="<?php echo esc_attr( $this->date() );?>" />
                <input type="hidden" name="author" value="<?php echo esc_attr( $this->author() );?>" />
                    <tr>
                        <td><label><b><?php echo ucfirst( $plural );?>:</b> <?php echo esc_html( $about );?></label></th>
                        <td class="td10 textcenter"><input name="pt[<?php echo esc_attr( $plural );?>]" type="checkbox" id="books" value="<?php echo esc_attr( $plural );?>" /></td>
                        <td class="td10 textcenter"><input name="pt[post]" type="checkbox" value="post" /></td>
                        <td class="td10 textcenter"><input name="pt[page]" type="checkbox" value="page" /></td>
                        <td class="td10 textcenter"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Activate', 'ptt-manager' );?>"></td>
                    </tr>
                </form>
            <?php }

            if ( get_option( $this->option_name . 'taxonomy_' . $plural ) ) {
                // Get Taxonomy Data
                if ( get_option( $this->option_name . 'taxonomy_' . $plural ) ) {
                    $data = get_option( $this->option_name . 'taxonomy_' . $plural );
                }

                // Set Checked Items
                $checked_plural = ( $data && ! empty( $data['pt'] ) && isset( $data['pt'][$plural] ) ) ? 'checked="checked"' : '';
                $checked_post = ( $data && ! empty( $data['pt'] ) && isset( $data['pt']['post'] ) ) ? 'checked="checked"' : '';
                $checked_page = ( $data && ! empty( $data['pt'] ) && isset( $data['pt']['page'] ) ) ? 'checked="checked"' : '';
                ?>
                    <tr>
                        <td><label><b><?php echo ucfirst( $plural );?>:</b> <?php echo esc_html( $about );?></label></th>
                        <td class="td10 textcenter"><input name="<?php echo esc_attr( $plural );?>" type="checkbox" value="1" <?php echo $checked_plural;?>/></td>
                        <td class="td10 textcenter"><input name="post" type="checkbox" value="1" <?php echo $checked_post;?>/></td>
                        <td class="td10 textcenter"><input name="page" type="checkbox" value="1" <?php echo $checked_page;?>/></td>
                        <td class="td10 textcenter"><a href="<?php echo admin_url();?>options-general.php?page=ptt-manager&tab=taxonomies&taxonomy=<?php echo esc_attr( $plural );?>" class="button"><?php _e( 'Modify', 'ptt-manager' );?></a></td>
                    </tr>
            <?php }
        }


        /**
         * @about Get Saved Options For Post Types or Taxonomies & Build Dropdown
         * @location templates/posttypes.php & templates/taxonomies.php
         * @call echo parent::editDropdown( 'posttype' );
         * @call echo parent::editDropdown( 'taxonomy' );
         * @param string $type Record type to build
         * @return string $html The dropdown form
         */
        final public function editDropdown( $type )
        {
            // Get Saved Names
            $names = get_option( $this->option_name . $type );

            // No Form If No Names
            $html = '';

            // Build Dropdown of Saved Names
            if ( $names ) {
                $html = '<form enctype="multipart/form-data" method="get" class="textright">';
                $html .= '<input type="hidden" name="page" value="' . $this->plugin_name . '">';
                $html .= '<input type="hidden" name="tab" value="' . esc_attr( $this->qString( 'tab' ) ) . '">';
                $html .= '<select name="' . esc_attr( $type ) . '" onchange="this.form.submit()">';

                if ( $this->qString( $type ) ) {
                    $html .= '<option value="">' . __( 'Select To Add', 'ptt-manager' ) . '</option>';
                } else {
                    $html .= '<option value="">' . __( 'Select To Edit', 'ptt-manager' ) . '</option>';
                }

                foreach ( $names as $name => $value ) {
                    // Set Selected
                    $selected = ( $this->qString( $type ) == $name ) ? ' selected' : '';

                    // Build Options
                    $html .= '<option value="' . esc_attr( $name ) . '"' . $selected .'>' . esc_html( ucfirst( $name ) ) . '</option>';
                }

                $html .= '</select>';
                $html .= '</form>';
            }

            return $html;
        }


        /**
         * @about Sanitize Text Strings | Strip tags, line breaks, tabs, whitespace, octets, validate UTF-8, < to entities, 
         * @return string Sanitized String
         */
        final public function sanitize( $string )
        {
            return sanitize_text_field( filter_var( $string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_BACKTICK ) );
        }


        /**
         * @about Sanitize Label/Name | Strip tags, line breaks, tabs, whitespace, octets, validate UTF-8, < to entities, lowercase, replace spaces and _ with -
         * @return string Sanitized String
         */
        final public function sanitizeName( $string )
        {
            // Sanitize String
            $filter = sanitize_text_field( filter_var( $string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_BACKTICK ) );

            // Replace spaces with dashes
            $no_spaces = preg_replace( '/\s/', '', $filter );

            // Replace underscores with dashes
            $no_underscores = str_replace( '_', '-', $no_spaces );

            // Lowercase & Return
            return strtolower( $no_underscores );
        }


        /**
         * @about Get All Post Type Keys
         * @return array Post Type Keys
         */
        final public function wpPosttypes()
        {
            global $wp_post_types;

            return array_keys( $wp_post_types );
        }


        /**
         * @about Created/Updated Inputs and Message
         * @call parent::createdUpdated( 'posttype' );
         * @call parent::createdUpdated( 'taxonomy' );
         */
        final public function createdUpdated( $type = '' )
        {
            // Required
            if ( ! empty( $type ) && $type == 'posttype' || ! empty( $type ) && $type == 'taxonomy' ) {

                if ( $this->field( $type, 'created' ) && $this->qString( $type ) ) {?>
                    <input type="hidden" name="updated" value="<?php echo esc_attr( $this->date() );?>" />
                    <input type="hidden" name="author" value="<?php echo esc_attr( $this->author() );?>" />
                    <input type="hidden" name="created" value="<?php echo $this->field( $type, 'created' );?>" />

                <?php // Ignore If Not Created, Thus Deleted
                if ( $this->field( $type, 'created' ) ) {?>
                    <p class="textcenter"><b><?php _e( 'Created on', 'ptt-manager' );?> <?php echo $this->field( $type, 'created' ); if ( $this->field( $type, 'updated' ) ) {?> <?php _e( 'and lasted updated on', 'ptt-manager' );?> <?php echo $this->field( $type, 'updated' ); }?> <?php _e( 'by', 'ptt-manager' );?> <?php echo $this->field( $type, 'author' );?>.</b></p>
                <?php }

                } else {?>
                    <input type="hidden" name="created" value="<?php echo esc_attr( $this->date() );?>" />
                    <input type="hidden" name="author" value="<?php echo esc_attr( $this->author() );?>" />
                <?php }
            }
        }


        /**
         * @about Create Checkbox Listing of Post Types
         * @location templates/taxonomies.php
         * @call echo parent::listPosttypes();
         * @return string $html Checkboxes of Post Type Names
         */
        final public function listPosttypes()
        {
            // Get Selected Post Types from Taxonomy Option for Checked Item
            $data = get_option( $this->option_name . 'taxonomy_' . $this->qString( 'taxonomy' ) );
            $selected_posttypes = ( ! empty( $data ) && isset( $data['pt'] ) && is_array( $data['pt'] ) ) ? $data['pt'] : '';

            // Not Included Items
            $skip = array(
                '1' => 'attachment',
                '2' => 'revision',
                '3' => 'nav_menu_item',
                '4' => 'custom_css',
                '5' => 'customize_changeset',
                '6' => 'deprecated_log'
            );

            // No Form If No Post Types
            $html = '';

            // Build List of Post Types, Will Skip All If No Saved Post Types
            foreach ( $this->wpPosttypes() as $posttype ) {
                if ( in_array( $posttype, $skip ) ){ continue; }

                // Set Checked
                $checked = ( isset( $selected_posttypes[$posttype] ) && $posttype == $selected_posttypes[$posttype] ) ? ' checked="checked"' : '';

                // Build Post Type Name
                if ( $posttype == "post" ) {
                    $name = __( 'Post (core)', 'ptt-manager' );

                } elseif ( $posttype == "page" ) {
                    $name = __( 'Page (core)', 'ptt-manager' );

                } else {
                    $name = esc_attr( ucfirst( $posttype ) );
                }

                // Build List
                $html .= '<p><label for="' . esc_attr( $posttype ) . '"><input name="pt[' . esc_attr( $posttype ) . ']" type="checkbox" id="' . esc_attr( $posttype ) . '" value="' . esc_attr( $posttype ) . '" ' . $checked . '/> ' . $name . '</label></p>';
            }

            return $html;
        }


        /**
         * @about Checkbox Listing of Taxonomies
         * @location templates/posttypes.php
         * @call echo parent::listTaxonomies();
         * @param string $type Should always be 'posttype'
         * @return string $html Checkboxes of Taxonomy Names
         */
        final public function listTaxonomies()
        {
            // Get Selected Taxonomies from Post Type Option for Checked Item
            $data = get_option( $this->option_name . 'posttype_' . $this->qString( 'posttype' ) );
            $selected_taxonomies = ( ! empty( $data ) && isset( $data['tn'] ) && is_array( $data['tn'] ) ) ? $data['tn'] : '';

            // Not Included Items
            $skip = array(
                'link_category' => 'link_category',
                'post_format' => 'post_format',
                'nav_menu' => 'nav_menu',
                'taxslug' => 'taxslug'
            );

            // No Form If No Post Types
            $html = '';

            // Build List of Taxonomies
            foreach ( get_taxonomies() as $taxonomy ) {
                if ( in_array( $taxonomy, $skip ) ){ continue; }

                // Set Checked
                $checked = ( isset( $selected_taxonomies[$taxonomy] ) && $taxonomy == $selected_taxonomies[$taxonomy] ) ? ' checked="checked"' : '';

                // Build Taxonomy Name
                if ( $taxonomy == "post_tag" ) {
                    $name = __( 'Tags (core)', 'ptt-manager' );

                } elseif ( $taxonomy == "category" ) {
                    $name = __( 'Categories (core)', 'ptt-manager' );

                } else {
                    $name = esc_attr( ucfirst( $taxonomy ) );
                }

                // Build List
                $html .= '<p><label for="' . esc_attr( $taxonomy ) . '"><input name="tn[' . esc_attr( $taxonomy ) . ']" type="checkbox" id="' . esc_attr( $taxonomy ) . '" value="' . esc_attr( $taxonomy ) . '" ' . $checked . '/> ' . $name . '</label></p>';
            }

            echo $html;
        }


        /**
         * @about Get the Current Date & Time
         */
        final public function date()
        {
            return date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
        }


        /**
         * @about Get Current Logged In User Display Name
         */
        final public function author()
        {
            $user = wp_get_current_user();
            return $user->display_name;
        }


        /**
         * @about Get All Plugin Options
         */
        final public function settings()
        {
            $html = '';

            // Saved Settings
            foreach ( wp_load_alloptions() as $option => $value ) {
                if ( strpos( $option, $this->plugin_name ) === 0 ) {
                    $html .= '<tr>';
                    $html .= '<td><label>' . esc_html( $option ) . '</label></td>';
                    $html .= '<td><input name="' . esc_attr( $option ) . '" type="text" value="' . esc_attr( $value ) . '" class="regular-text" readonly="readonly" /></td>';
                    $html .= '</tr>';
                }
            }

            return $html;
        }


        /**
         * @about Get Saved Option Data For Inputs/Display
         * @location templates/posttypes.php & templates/taxonomies.php
         * @call echo parent::field( 'posttype', 'plural' );
         * @param string $option The option name to look up
         * @param string $field The field to get data for
         * @return string Input Field Data
         */
        final public function field( $option = '', $field = '' )
        {
            if ( ! empty( $option ) && ! empty( $field ) ) {
                // Block Post Type / Taxonomy
                if ( $option == "posttype_block" || $option == "taxonomy_block" ) {
                    $data = get_option( $this->option_name . $option );

                // Post Type/Taxonomy Data
                } else {
                    // Option Name Example: ptt-manager_posttype_books
                    $data = get_option( $this->option_name . $option . '_' . $this->qString( $option ) );
                }

                // Return Data From Field
                if ( isset( $data[$field] ) ) {
                    return esc_attr( $data[$field] );

                }
            }
        }


        /**
         * @about Get Query String Item
         * @param string $get Query String Get Item
         * @return string Query String Item Sanitized
         */
        final public function qString( $get )
        {
            // Lowercase & Sanitize String
            $filter = strtolower( filter_input( INPUT_GET, $get, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_BACKTICK ) );

            // Return No Spaces/Tabs, Stripped/Cleaned String
            return sanitize_text_field( preg_replace( '/\s/', '', $filter ) );
        }


        /**
         * @about Form Validation
         */
        final public function validate()
        {
            // Plugin Admin Area Only
            if ( $this->qString( 'page' ) != $this->plugin_name ) {
                wp_die( __( 'You are not authorized to perform this action.', 'ptt-manager' ) );
            }

            // Validate Nonce Action
            if( ! check_admin_referer( $this->option_name . 'action', $this->option_name . 'nonce' ) ) {
                wp_die( __( 'You are not authorized to perform this action.', 'ptt-manager' ) );
            }
        }
    }
}
