<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }?>

<?php echo parent::editDropdown( 'taxonomy' );?>

<?php if ( filter_input( INPUT_GET, "taxonomy" ) ) {?>
    <h3><?php _e( 'Edit Taxonomy', 'ptt-manager' );?></h3>
    <p><?php _e( 'Edit your custom taxonomy. Taxonomies are categories, helping you organize content within Post Types.', 'ptt-manager' );?></p>
<?php  } else {?>
    <h3><?php _e( 'Add Taxonomy', 'ptt-manager' );?></h3>
    <p><?php _e( 'Create a custom taxonomy. Taxonomies are categories, helping you organize content within Post Types.', 'ptt-manager' );?></p>
<?php  }?>

<form enctype="multipart/form-data" method="post" action="">
<?php wp_nonce_field( $this->option_name . 'action', $this->option_name . 'nonce' );?>
<input type="hidden" name="type" value="taxonomy" />
<?php parent::createdUpdated( 'taxonomy' );?>

    <table class="form-table">
    <tr>
        <td class="td"><label for="plural"><?php _e( 'Plural Name', 'ptt-manager' );?></label><span class="required">*</span></td>
        <td><input name="plural" type="text" id="plural" value="<?php echo parent::field( 'taxonomy', 'plural' );?>" class="regular-text" placeholder="(e.g. Mysteries Genre, Classics, Local Events)" required="true" />
            <p class="description"><?php _e( 'The plural (more than one) name for the taxonomy. Alphanumeric, capitalization, spaces, max 32 character length!', 'ptt-manager' );?></p></td>
    </tr>
    <tr>
        <td class="td"><label for="singular"><?php _e( 'Singular Name', 'ptt-manager' );?></label><span class="required">*</span></td>
        <td><input name="singular" type="text" id="singular" value="<?php echo parent::field( 'taxonomy', 'singular' );?>" class="regular-text" placeholder="(e.g. Mystery Genre, Classic, Local Event)" required="true" />
            <p class="description"><?php _e( 'The singular (non-plural) name for the taxonomy. Alphanumeric, capitalization, spaces, max 32 character length!', 'ptt-manager' );?></p></td>
    </tr>
    <tr>
        <td class="td"><label for="slug"><?php _e( 'Slug Name', 'ptt-manager' );?></label><span class="required">*</span></td>
        <td><input name="slug" type="text" id="slug" value="<?php echo parent::field( 'taxonomy', 'slug' );?>" class="regular-text" placeholder="(e.g. mystery, classic, local_event)" required="true" />
            <p class="description"><?php _e( 'The permalink slug name for this taxonomy. Alphanumeric, lowercase, dashes, no spaces, max 32 character length!', 'ptt-manager' );?></p></td>
    </tr>
    <tr>
        <td class="td"><label><?php _e( 'Post Type Support', 'ptt-manager' );?><span class="required">*</span></label></td>
        <td><p class="description"><?php _e( 'Attach core & registered post types to this taxonomy. Default is "Page" if none are selected.', 'ptt-manager' );?></p>
            <?php echo parent::listPosttypes();?></td>
    </tr>
    </table>

    <h4><?php _e( 'Optional Settings', 'ptt-manager' );?></h4>

    <table class="form-table">
    <tr>
        <td class="td"><label for="description"><?php _e( 'Description', 'ptt-manager' );?></label></td>
        <td><fieldset><label for="description"><textarea name="description" id="description" class="description"><?php echo parent::field( 'taxonomy', 'description' );?></textarea><br /><span class="description"><?php _e( 'Describe what the taxonomy is about. The description will appear on archive page, if the archive page displays descriptions.', 'ptt-manager' );?></span></label></fieldset></td>
    </tr>
    <tr>
        <td class="td"><label for="public"><?php _e( 'Public Access', 'ptt-manager' );?></label></td>
        <td><fieldset><label for="public"><input name="public" type="checkbox" id="public" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'taxonomy', 'public' ), 1 );?>/><span class="description"><?php _e( 'Select to "not" have the taxonomy visible/usable for visitors and authors on the website front-end.', 'ptt-manager' );?></span></label></fieldset></td>
    </tr>
    <tr>
        <td class="td"><label for="hierarchical"><?php _e( 'Hierarchical', 'ptt-manager' );?></label></td>
        <td><fieldset><label for="hierarchical"><input name="hierarchical" type="checkbox" id="public" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'taxonomy', 'search' ), 1 );?>/><span class="description"><?php _e( 'Select to "not" allow the taxonomy to have parent->child relationships.', 'ptt-manager' );?></span></label></fieldset></td>
    </tr>
    <tr>
        <td class="td"><label for="showui"><?php _e( 'Admin Area Viewable', 'ptt-manager' );?></label></td>
        <td><fieldset><label for="showui"><input name="showui" type="checkbox" id="showui" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'taxonomy', 'showui' ), 1 );?>/><span class="description"><?php _e( 'Select to "remove" the taxonomy from the WP Admin Area menus & editor page.', 'ptt-manager' );?></span></label></fieldset></td>
    </tr>
    </table>

    <div class="textcenter"><?php submit_button();?></div>

    <?php if ( filter_input( INPUT_GET, "taxonomy" ) ) {?>
        <div class="left"><input type="checkbox" name="delete" value="1" /><?php _e( 'Delete This Record', 'ptt-manager' );?></div>
    <?php }?>

</form>
