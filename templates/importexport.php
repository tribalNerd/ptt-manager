<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }?>

<h3><?php _e( 'Export / Import Post Types', 'ptt-manager' );?></h3>
<p><?php _e( 'Export and import post type data for the Post Types & Taxonomies Manager plugin. Export does not include presets! Importing will override saved Post Type data from this plugin only!', 'ptt-manager' );?></p>

<form enctype="multipart/form-data" method="post" action="">
<?php wp_nonce_field( $this->option_name . 'action', $this->option_name . 'nonce' );?>
<input type="hidden" name="type" value="import" />

    <table>
    <tr>
        <td class="td"><label for="singular"><?php _e( 'Post Types Export', 'ptt-manager' )?></label></td>
        <td><textarea readonly="readonly"><?php apply_filters( $this->plugin_name . '_export', 'posttype' );?></textarea>
            <p class="description"><?php _e( 'JSON encoded export data for saved taxonomies.', 'ptt-manager' )?></p></td>
    </tr>
    <tr>
        <td class="td"><label for="plural"><?php _e( 'Import Post Types', 'ptt-manager' )?></label></td>
        <td><textarea name="posttypes"></textarea></td>
    </tr>
    </table>

    <div class="textcenter"><?php submit_button( 'Import' );?></div>

</form>

<h3><?php _e( 'Export / Import Taxonomies', 'ptt-manager' );?></h3>
<p><?php _e( 'Export and import taxonomy data for the Post Types & Taxonomies Manager plugin. Export does not include presets! Importing will override saved Taxonomy data from this plugin only!', 'ptt-manager' );?></p>

<form enctype="multipart/form-data" method="post" action="">
<?php wp_nonce_field( $this->option_name . 'action', $this->option_name . 'nonce' );?>
<input type="hidden" name="type" value="import" />

    <table>
    <tr>
        <td class="td"><label for="singular"><?php _e( 'Taxonomies Export', 'ptt-manager' )?></label></td>
        <td><textarea readonly="readonly"><?php apply_filters( $this->plugin_name . '_export', 'taxonomy' );?></textarea>
            <p class="description"><?php _e( 'JSON encoded export data for saved taxonomies.', 'ptt-manager' )?></p></td>
    </tr>
    <tr>
        <td class="td"><label for="plural"><?php _e( 'Import Taxonomies', 'ptt-manager' )?></label></td>
        <td><textarea name="taxonomies"></textarea></td>
    </tr>
    </table>

    <div class="textcenter"><?php submit_button( 'Import' );?></div>

</form>
