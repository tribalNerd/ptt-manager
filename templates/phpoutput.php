<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }?>

<h3><?php _e( 'PHP Output Post Types', 'ptt-manager' );?></h3>
<p><?php _e( 'Post Type PHP code snips used by the Post Type & Taxonomy Manager plugin. Does not include presets!', 'ptt-manager' );?></p>

<form>

    <table>
    <tr>
        <td class="td"><label for="singular"><?php _e( 'Post Types', 'ptt-manager' )?></label></td>
        <td><textarea readonly="readonly"><?php apply_filters( $this->plugin_name . '_php', 'posttypes' );?></textarea>
            <p class="description"><?php _e( 'PHP Code Output for the themes functions.php file.', 'ptt-manager' )?></p></td>
    </tr>
    </table>

</form>

<hr />

<form enctype="multipart/form-data" method="post" action="">
<?php wp_nonce_field( $this->option_name . 'action', $this->option_name . 'nonce' );?>
<input type="hidden" name="type" value="posttype_block" />

    <table>
    <tr>
        <td class="td"><label for="posttype_block"><?php _e( 'Block Post Type Rendering', 'ptt-manager' )?></label></td>
        <td><input name="block" type="checkbox" id="posttype_block" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'posttype_block', 'block' ), 1 );?>/> 
            <span class="description"><?php _e( 'Stop the Post Type & Taxonomy Manager plugin from rendering saved Post Types. All other features work. This allows you to use the plugin to store post types, thus using the PHP output code within your themes functions.php file to render the post types.', 'ptt-manager' )?></span></td>
    </tr>
    </table>

    <div class="textcenter"><?php submit_button( 'Save Blocker Settings' );?></div>

</form>

<hr />

<h3><?php _e( 'PHP Output Taxonomies', 'ptt-manager' );?></h3>
<p><?php _e( 'Taxonomy PHP code snips used by the Post Type & Taxonomy Manager plugin. Does not include presets!', 'ptt-manager' );?></p>

<form>

    <table>
    <tr>
        <td class="td"><label for="singular"><?php _e( 'Taxonomies', 'ptt-manager' )?></label></td>
        <td><textarea readonly="readonly"><?php apply_filters( $this->plugin_name . '_php', 'taxonomies' );?></textarea>
            <p class="description"><?php _e( 'PHP Code Output for the themes functions.php file.', 'ptt-manager' )?></p></td>
    </tr>
    </table>

</form>

<hr />

<form enctype="multipart/form-data" method="post" action="">
<?php wp_nonce_field( $this->option_name . 'action', $this->option_name . 'nonce' );?>
<input type="hidden" name="type" value="taxonomy_block" />

    <table>
    <tr>
        <td class="td"><label for="taxonomy_block"><?php _e( 'Block Taxonomy Rendering', 'ptt-manager' )?></label></td>
        <td><input name="block" type="checkbox" id="taxonomy_block" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'taxonomy_block', 'block' ), 1 );?>/> 
            <span class="description"><?php _e( 'Stop the Post Type & Taxonomy Manager plugin from rendering saved Taxonomies. All other features work. This allows you to use the plugin to store taxonomies, thus using the PHP output code within your themes functions.php file to render the taxonomies.', 'ptt-manager' )?></span></td>
    </tr>
    </table>

    <div class="textcenter"><?php submit_button( 'Save Blocker Settings' );?></div>

</form>
