<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }

// Wordpress uninstall check
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit; }


/**
 * @about Uninstall Plugin Options
 */
class PTTManager_Uninstall
{
    /**
     * @about Run Uninstall Method
     */
    function __construct()
    {
        // Valid Users Only
        if( ! is_user_logged_in() && ! current_user_can( 'manage_options' ) ) { wp_die( 'Unauthorized Access.', 'ptt-manager' ); }
        
        // Run Uninstall
        $this->uninstall();																										/** run uninstall */
    }


    /**
     * @about Delete Plugin Options
     */
    function uninstall()
    {
        foreach( wp_load_alloptions() as $option => $value ) {
            if ( strpos( $option, 'ptt-manager' ) === 0 ) {
                delete_option( $option );
            }
        }

        return;
    }
}

// Run Uninstall
if ( class_exists( 'PTTManager_Uninstall' ) ) {
    new PTTManager_Uninstall();
}
