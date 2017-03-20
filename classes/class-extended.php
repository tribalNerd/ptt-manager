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
 * @method filterInputGet() INPUT_GET Filter
 * @method sanitize()       Sanitize Text Strings
 * @method sanitizeName()   Sanitize Label/Name
 * @method wpPosttypes()    Get All Post Type Keys
 * @method validate()       Simple Form Validation
 * @method createdUpdated() Created/Updated Inputs and Message
 * @method listPosttypes()  Create Checkbox Listing of Post Types
 * @method listTaxonomies() Checkbox Listing of Taxonomies
 * @method date()           Get the Current Date & Time
 * @method author()         Get Current Logged In User Display Name
 * @method settings()       Get All Plugin Options
 * @method protect()        Admin Area Protection
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
        
        // Plugin filename.php
        public $plugin_file;
        
        // Current Plugin Version
        public $plugin_version;
        
        // Plugin Menu Name
        public $menu_name;
        
        // Path To Plugin Templates
        public $templates;

        // Tab Names
        public $tabs;

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
            $this->plugin_file      = PTT_MANAGER_PLUGIN_FILE;
            $this->plugin_version   = PTT_MANAGER_VERSION;
            $this->menu_name        = PTT_MANAGER_MENU_NAME;
            $this->templates        = PTT_MANAGER_TEMPLATES;

            // Tabs Names: &tab=home
            $this->tabs = array( 
                'home'          => esc_html__( 'Home', 'ptt-manager' ),
                'posttypes'     => esc_html__( 'Post Types', 'ptt-manager' ),
                'taxonomies'    => esc_html__( 'Taxonomies', 'ptt-manager' ),
                'importexport'  => esc_html__( 'Import/Export', 'ptt-manager' ),
                'phpoutput'     => esc_html__( 'PHP Output', 'ptt-manager' ),
                'settings'      => esc_html__( 'Saved Settings', 'ptt-manager' ),
                'templates'     => esc_html__( 'Templates', 'ptt-manager' ),
            );
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
                    $message = esc_html__( 'Post Type Settings Updated!' );
                break;

                case 'taxonomyupdate':
                    $message = esc_html__( 'Taxonomy Settings Updated!' );
                break;

                case 'blockerupdate':
                    $message = esc_html__( 'Blocker Settings Updated!' );
                break;

                case 'importupdate':
                    $message = esc_html__( 'Settings Imported!' );
                break;

                case 'settingsupdate':
                    $message = esc_html__( 'Settings Deleted!' );
                break;

                case 'duplicate':
                    $message = esc_html__( 'The Post Type / Taxonomy Is Already Registered or Reserved!' );
                break;

                case 'posttypeerror':
                    $message = esc_html__( 'Post Type Settings Failed To Update!' );
                break;

                case 'taxonomyerror':
                    $message = esc_html__( 'Taxonomy Settings Failed To Update! ' );
                break;

                case 'blockererror':
                    $message = esc_html__( 'Blocker Settings Failed To Update!' );
                break;

                case 'importerror':
                    $message = esc_html__( 'Import Failed!' );
                break;

                case 'unknownerror':
                    $message = esc_html__( 'Error: No Post Data!' );
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
            if ( ! get_post_type_object( $this->sanitizeName( $plural ) ) ) {?>
                <form enctype="multipart/form-data" method="post" action="options.php">
                <?php settings_fields( $this->plugin_name );?>
                <?php do_settings_sections( $this->plugin_name );?>
                <input type="hidden" name="type" value="posttype" />
                <input type="hidden" name="plural" value="<?php echo $plural;?>" />
                <input type="hidden" name="singular" value="<?php echo $singular;?>" />
                <input id="dashicons_picker_<?php echo $plural;?>" name="dashicons_picker_<?php echo $plural;?>" type="hidden" value="" />
                    <tr>
                        <td><label for="<?php echo $plural;?>"><b><?php echo ucfirst( $plural );?>:</b> <?php echo $about;?></label></th>
                        <td class="td10 textcenter"><input class="button dashicons-picker" type="button" value="Icon" data-target="#dashicons_picker_<?php echo $plural;?>" /></td>
                        <td class="td10 textcenter"><input type="submit" name="submit" id="submit" class="button button-primary" value="Activate"></td>
                    </tr>
                </form>
            <?php } else {
                // Get Taxonomy Data
                if ( get_option( $this->plugin_name . '_posttype_' . $plural ) ) {
                    $data = get_option( $this->plugin_name . '_posttype_' . $plural );
                }

                // Dashicon
                $dashicon = ( $data && isset( $data['dashicons_picker_' . $plural] ) ) ? $data['dashicons_picker_' . $plural] : '';
                ?>
                    <tr>
                        <td><label for="<?php echo $plural;?>"><b><?php echo ucfirst( $plural );?>:</b> <?php echo $about;?></label></th>
                        <td class="td10 textcenter"><span class="dashicons <?php echo $dashicon;?>"></span></td>
                        <td class="td10 textcenter"><a href="<?php echo admin_url();?>options-general.php?page=ptt-manager&tab=posttypes&posttype=<?php echo $plural;?>" class="button">Modify</a></td>
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
            if ( ! get_taxonomy( $this->sanitizeName( $plural ) ) ) {?>
                <form enctype="multipart/form-data" method="post" action="options.php">
                <?php settings_fields( $this->plugin_name );?>
                <?php do_settings_sections( $this->plugin_name );?>
                <input type="hidden" name="type" value="taxonomy" />
                <input type="hidden" name="plural" value="<?php echo $plural;?>" />
                <input type="hidden" name="singular" value="<?php echo $singular;?>" />
                <input type="hidden" name="slug" value="<?php echo $plural;?>" />
                    <tr>
                        <td><label><b><?php echo ucfirst( $plural );?>:</b> <?php echo $about;?></label></th>
                        <td class="td10 textcenter"><input name="pt[<?php echo $plural;?>]" type="checkbox" id="books" value="<?php echo $plural;?>" /></td>
                        <td class="td10 textcenter"><input name="pt[post]" type="checkbox" value="post" /></td>
                        <td class="td10 textcenter"><input name="pt[page]" type="checkbox" value="page" /></td>
                        <td class="td10 textcenter"><input type="submit" name="submit" id="submit" class="button button-primary" value="Activate"></td>
                    </tr>
                </form>
            <?php } else {
                // Get Taxonomy Data
                if ( get_option( $this->plugin_name . '_taxonomy_' . $plural ) ) {
                    $data = get_option( $this->plugin_name . '_taxonomy_' . $plural );
                }

                // Set Checked Items
                $checked_plural = ( $data && ! empty( $data['pt'] ) && isset( $data['pt'][$plural] ) ) ? 'checked="checked"' : '';
                $checked_post = ( $data && ! empty( $data['pt'] ) && isset( $data['pt']['post'] ) ) ? 'checked="checked"' : '';
                $checked_page = ( $data && ! empty( $data['pt'] ) && isset( $data['pt']['page'] ) ) ? 'checked="checked"' : '';
                ?>
                    <tr>
                        <td><label><b><?php echo ucfirst( $plural );?>:</b> <?php echo $about;?></label></th>
                        <td class="td10 textcenter"><input name="<?php echo $plural;?>" type="checkbox" value="1" <?php echo $checked_plural;?>/></td>
                        <td class="td10 textcenter"><input name="post" type="checkbox" value="1" <?php echo $checked_post;?>/></td>
                        <td class="td10 textcenter"><input name="page" type="checkbox" value="1" <?php echo $checked_page;?>/></td>
                        <td class="td10 textcenter"><a href="<?php echo admin_url();?>options-general.php?page=ptt-manager&tab=taxonomies&taxonomy=<?php echo $plural;?>" class="button">Modify</a></td>
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
            $names = get_option( $this->plugin_name . '_' . $type );

            // No Form If No Names
            $html = '';

            // Build Dropdown of Saved Names
            if ( $names ) {
                $html = '<form enctype="multipart/form-data" method="get" class="textright">';
                $html .= '<input type="hidden" name="page" value="' . $this->plugin_name . '">';
                $html .= '<input type="hidden" name="tab" value="' . esc_attr( $this->filterInputGet( 'tab' ) ) . '">';
                $html .= '<select name="' . esc_attr( $type ) . '" onchange="this.form.submit()">';

                if ( filter_input( INPUT_GET, $type ) ) {
                    $html .= '<option value="">' . esc_html__( 'Select To Add', 'ptt-manager' ) . '</option>';
                } else {
                    $html .= '<option value="">' . esc_html__( 'Select To Edit', 'ptt-manager' ) . '</option>';
                }

                foreach ( $names as $name => $value ) {
                    // Set Selected
                    $selected = ( $this->filterInputGet( $type ) == $name ) ? ' selected' : '';

                    // Build Options
                    $html .= '<option value="' . esc_attr( $name ) . '"' . $selected .'>' . esc_html( ucfirst( $name ) ) . '</option>';
                }

                $html .= '</select>';
                $html .= '</form>';
            }

            return $html;
        }


        /**
         * @about INPUT_GET for Taxonomy, Post Type, Tab & Page
         * @return string Sanitized INPUT_GET
         */
        final public function filterInputGet( $name )
        {
            // Lowercase & Sanitize String
            $filter = strtolower( filter_input( INPUT_GET, $name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_BACKTICK ) );

            // Return No Spaces/Tabs, Stripped/Cleaned String
            return sanitize_text_field( preg_replace( '/\s/', '', $filter ) );
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
         * @about Simple Form Validation
         * @note  Full Valiation Via Settings API
         */
        final public function validate()
        {
            // Validate Post Location
            if ( strpos( filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL ), "options.php" ) === false ) {
                wp_die( __( 'You are not authorized to perform this action.', 'ptt-manager' ) );
            }

            // Simple Referer Check
            if ( strpos( filter_input( INPUT_POST, '_wp_http_referer', FILTER_UNSAFE_RAW ), $this->plugin_name ) === false ) {
                wp_die( __( 'You are not authorized to perform this action.', 'ptt-manager' ) );
            }
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

                if ( filter_input( INPUT_GET, $type ) ) {?>
                    <input type="hidden" name="updated" value="<?php echo $this->date();?>" />
                    <input type="hidden" name="author" value="<?php echo $this->author();?>" />
                    <input type="hidden" name="created" value="<?php echo apply_filters( $this->plugin_name . '_field', $type, 'created' );?>" />

                <?php // Ignore If Not Created, Thus Deleted
                if ( apply_filters( $this->plugin_name . '_field', $type, 'created' ) ) {?>
                    <p class="textcenter"><b><?php _e( 'Created on' );?> <?php echo apply_filters( $this->plugin_name . '_field', $type, 'created' ); if ( apply_filters( $this->plugin_name . '_field', $type, 'updated' ) ) {?> <?php _e( 'and lasted updated on' );?> <?php echo apply_filters( $this->plugin_name . '_field', $type, 'updated' ); }?> <?php _e( 'by' );?> <?php echo apply_filters( $this->plugin_name . '_field', $type, 'author' );?>.</b></p>
                <?php }

                } else {?>
                    <input type="hidden" name="created" value="<?php echo $this->date();?>" />
                    <input type="hidden" name="author" value="<?php echo $this->author();?>" />
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
            $data = get_option( $this->plugin_name . '_taxonomy_' . $this->filterInputGet( 'taxonomy' ) );
            $selected_posttypes = ( ! empty( $data ) && isset( $data['pt'] ) && is_array( $data['pt'] ) ) ? $data['pt'] : '';

            // Not Included Items
            $skip = array(
                '1' => 'attachment',
                '2' => 'revision',
                '3' => 'nav_menu_item',
                '4' => 'custom_css',
                '5' => 'customize_changeset'
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
                    $name = esc_attr__( 'Post (core)', 'ptt-manager' );

                } elseif ( $posttype == "page" ) {
                    $name = esc_attr__( 'Page (core)', 'ptt-manager' );

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
            $data = get_option( $this->plugin_name . '_posttype_' . $this->filterInputGet( 'posttype' ) );
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
                    $name = esc_attr__( 'Tags (core)', 'ptt-manager' );

                } elseif ( $taxonomy == "category" ) {
                    $name = esc_attr__( 'Categories (core)', 'ptt-manager' );

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
                    $html .= '<td><label>' . esc_attr( $option ) . '</label></td>';
                    $html .= '<td><input name="' . esc_attr( $option ) . '" type="text" value="' . esc_attr( $value ) . '" class="regular-text" readonly="readonly" /></td>';
                    $html .= '</tr>';
                }
            }

            return $html;
        }


        /**
         * @about Protect Admin from Lower Users
         */
	final public function protect()
        {
            // Nobody Can Access
            $user_can_access = false;

            // Authorized Users Can Access
            if ( current_user_can( 'manage_options' ) ) {
                $user_can_access = true;
            }

            // Redirect Invalid Users
            if ( ! $user_can_access ) {
                wp_safe_redirect( admin_url( 'index.php' ) );
                exit;
            }
	}
    }
}
