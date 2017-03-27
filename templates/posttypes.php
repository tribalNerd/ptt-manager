<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }

echo parent::editDropdown( 'posttype' );

if ( filter_input( INPUT_GET, "posttype" ) ) {?>
    <h3><?php _e( 'Edit Post Type', 'ptt-manager' );?></h3>
    <p><?php _e( 'Edit your custom post type. By default, post types are public, archived, will display on the admin area menu, and support all editor features.', 'ptt-manager' );?></p>
<?php  } else {?>
    <h3><?php _e( 'Add Post Type', 'ptt-manager' );?></h3>
    <p><?php _e( 'Create a custom post type. By default, post types are public, archived, will display on the admin area menu, and support all editor features.', 'ptt-manager' );?></p>
<?php  }?>

<form enctype="multipart/form-data" method="post" action="">
<?php wp_nonce_field( $this->option_name . 'action', $this->option_name . 'nonce' );?>
<input type="hidden" name="type" value="posttype" />
<?php parent::createdUpdated( 'posttype' );?>

    <table class="form-table">
    <tr>
        <td class="td"><label for="plural"><?php _e( 'Plural Name', 'ptt-manager' );?></label><span class="required">*</span></td>
        <td><input name="plural" type="text" id="plural" value="<?php echo parent::field( 'posttype', 'plural' );?>" class="regular-text" placeholder="(e.g. Cool Games, Best Movies, Top Advice, Local News)" required="true" />
            <p class="description"><?php _e( 'The plural (more than one) name of the post type. Alphanumeric, capitalization, spaces, max 32 character length!', 'ptt-manager' );?></p></td>
    </tr>
    <tr>
        <td class="td"><label for="singular"><?php _e( 'Singular Name', 'ptt-manager' );?></label><span class="required">*</span></td>
        <td><input name="singular" type="text" id="singular" value="<?php echo parent::field( 'posttype', 'singular' );?>" class="regular-text" placeholder="(e.g. Cool Game, Best Movie, Top Advice, Local News)" required="true" />
            <p class="description"><?php _e( 'The singular (non-plural) name of the post type. Alphanumeric, capitalization, spaces, max 32 character length!', 'ptt-manager' );?></p></td>
    </tr>
    <?php $dashicon = apply_filters( $this->plugin_name . '_dashicon', 'dashicons_picker_' );?>
    <tr>
        <td class="td"><label><?php _e( 'Menu Icon', 'ptt-manager' );?></label></td>
        <td><input name="<?php echo $dashicon;?>" type="text" id="<?php echo $dashicon;?>" value="<?php echo parent::field( 'posttype', $dashicon );?>" class="regular-text" /> <input class="button dashicons-picker" type="button" value="Select Icon" data-target="#<?php echo $dashicon;?>" />
            <p class="description"><?php _e( 'Select the WordPress Dashicon to display next to the menu item.', 'ptt-manager' );?></p></td>
    </tr>
    <tr>
        <td class="td"><label><?php _e( 'Taxonomy Support', 'ptt-manager' );?></label></td>
        <td><p class="description"><?php _e( 'Select to use core & registered taxonomies with this post type.', 'ptt-manager' );?></p>
            <?php echo parent::listTaxonomies();?></td>
    </tr>
    </table>

    <h4><?php _e( 'Optional Settings', 'ptt-manager' );?></h4>

    <table class="form-table">
    <tr>
        <td class="td"><label for="slug"><?php _e( 'Slug Name', 'ptt-manager' );?></label></td>
        <td><input name="slug" type="text" id="slug" value="<?php echo parent::field( 'posttype', 'slug' );?>" class="regular-text" placeholder="(e.g. gaming, movies, advice, news)" />
            <p class="description"><?php _e( 'The permalink slug name for this post type. If not set, the Plural Name will be used. Alphanumeric, lowercase, dashes, no spaces, max 32 character length!', 'ptt-manager' );?></p></td>
    </tr>
    <tr>
        <td class="td"><label for="menuname"><?php _e( 'Menu Name', 'ptt-manager' );?></label></td>
        <td><input name="menuname" type="text" id="smenuname" value="<?php echo parent::field( 'posttype', 'menuname' );?>" class="regular-text" placeholder="(e.g. My Games, Cool Movies, Good News)" />
            <p class="description"><?php _e( 'If not set, the Plural Name will be used. Alphanumeric, capitalization, spaces, max 32 character length!', 'ptt-manager' );?></p></td>
    </tr>
    <tr>
        <td class="td"><label for="description"><?php _e( 'Description', 'ptt-manager' );?></label></td>
        <td><fieldset><label for="description"><textarea name="description" id="description" class="description"><?php echo parent::field( 'posttype', 'description' );?></textarea><br /><span class="description"><?php _e( 'Describe what the post type is about. Not natively used by templates or within the admin area.', 'ptt-manager' );?></span></label></fieldset></td>
    </tr>
    <tr>
        <td class="td"><label for="public"><?php _e( 'Public Access', 'ptt-manager' );?></label></td>
        <td><fieldset><label for="public"><input name="public" type="checkbox" id="public" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'posttype', 'public' ), 1 );?>/><span class="description"><?php _e( 'Select to "not" have the post type visible/usable for visitors and authors. If checked, this will make the post type unsearchable, unviewable, and remove it from admin area menus.', 'ptt-manager' );?></span></label></fieldset></td>
    </tr>
    <tr>
        <td class="td"><label for="search"><?php _e( 'Website Searchable', 'ptt-manager' );?></label></td>
        <td><fieldset><label for="search"><input name="search" type="checkbox" id="search" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'posttype', 'search' ), 1 );?>/><span class="description"><?php _e( 'Select to "not" have the post type searchable on the Website.', 'ptt-manager' );?></span></label></fieldset></td>
    </tr>
    <tr>
        <td class="td"><label for="queryable"><?php _e( 'Website Viewable', 'ptt-manager' );?></label></td>
        <td><fieldset><label for="queryable"><input name="queryable" type="checkbox" id="queryable" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'posttype', 'queryable' ), 1 );?>/><span class="description"><?php _e( 'Select to "not" have the post type viewable on the Website. Disables: domain.com/post-type-slug/post-name/', 'ptt-manager' );?></span></label></fieldset></td>
    </tr>
    <tr>
        <td class="td"><label for="archive"><?php _e( 'Archive URL', 'ptt-manager' );?></label></td>
        <td><fieldset><label for="archive"><input name="archive" type="checkbox" id="archive" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'posttype', 'archive' ), 1 );?>/><span class="description"><?php _e( 'Select to "not" allow the post type to to have a unique archive URL.', 'ptt-manager' );?></span></label></fieldset></td>
    </tr>
    <tr>
        <td class="td"><label for="archiveslug"><?php _e( 'Archive Slug', 'ptt-manager' );?></label></td>
        <td><fieldset><label for="archiveslug"><input name="archiveslug" type="text" id="archiveslug" value="<?php echo parent::field( 'posttype', 'archiveslug' );?>" class="regular-text" placeholder="(e.g. gaming, movies, advice, news)" />
            <p class="description"><?php _e( 'If Archive URL is selected, then optionally set an Archive Slug to be used instead of the default Post Type plural name.', 'ptt-manager' );?></p></label></fieldset></td>
    </tr>
    <tr>
        <td class="td"><label for="showui"><?php _e( 'Admin Area Viewable', 'ptt-manager' );?></label></td>
        <td><fieldset><label for="showui"><input name="showui" type="checkbox" id="showui" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'posttype', 'showui' ), 1 );?>/><span class="description"><?php _e( 'Select to "remove" the post type from the WP Admin Area menus & editor page.', 'ptt-manager' );?></span></label></fieldset></td>
    </tr>
    <tr>
        <td class="td"><label for="showrest"><?php _e( 'REST API Viewable', 'ptt-manager' );?></label></td>
        <td><fieldset><label for="showrest"><input name="showrest" type="checkbox" id="showrest" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'posttype', 'showrest' ), 1 );?>/><span class="description"><?php _e( 'Select to "show" the post type data within the WordPress REST API.', 'ptt-manager' );?></span></label></fieldset></td>
    </tr>
    <tr>
        <td class="td"><label for="restbase"><?php _e( 'REST API Slug', 'ptt-manager' );?></label></td>
        <td><fieldset><label for="restbase"><input name="restbase" type="text" id="restbase" value="<?php echo parent::field( 'posttype', 'restbase' );?>" class="regular-text" placeholder="(e.g. gaming, movies, advice, news)" />
            <p class="description"><?php _e( 'The slug name for the WordPress REST API. Defaults to Post Type plural name.', 'ptt-manager' );?></p></label></fieldset></td>
    </tr>
    <tr>
        <td class="td"><label for="position"><?php _e( 'Menu Position', 'ptt-manager' );?></label></td>
        <td><fieldset><label for="position">
                    <select name="position">
                        <option value=""<?php echo ( apply_filters( $this->plugin_name . '_field', 'posttype', 'position' ) == "" || ! apply_filters( $this->plugin_name . '_field', 'posttype', 'position' ) ) ? ' selected' : '';?>><?php _e( 'Select An Option', 'ptt-manager' );?></option>
                        <option value="5"<?php echo ( apply_filters( $this->plugin_name . '_field', 'posttype', 'position' ) == "5" ) ? ' selected' : '';?>><?php _e( '5 - Below Posts', 'ptt-manager' );?></option>
                        <option value="10"<?php echo ( apply_filters( $this->plugin_name . '_field', 'posttype', 'position' ) == "10" ) ? ' selected' : '';?>><?php _e( '10 - Below Media', 'ptt-manager' );?></option>
                        <option value="15"<?php echo ( apply_filters( $this->plugin_name . '_field', 'posttype', 'position' ) == "15" ) ? ' selected' : '';?>><?php _e( '15 - Below Links', 'ptt-manager' );?></option>
                        <option value="20"<?php echo ( apply_filters( $this->plugin_name . '_field', 'posttype', 'position' ) == "20" ) ? ' selected' : '';?>><?php _e( '20 - Below Pages', 'ptt-manager' );?></option>
                        <option value="25"<?php echo ( apply_filters( $this->plugin_name . '_field', 'posttype', 'position' ) == "25" ) ? ' selected' : '';?>><?php _e( '25 - Below Comments (default)', 'ptt-manager' );?></option>
                        <option value="60"<?php echo ( apply_filters( $this->plugin_name . '_field', 'posttype', 'position' ) == "60" ) ? ' selected' : '';?>><?php _e( '60 - Below 1st Separator', 'ptt-manager' );?></option>
                        <option value="65"<?php echo ( apply_filters( $this->plugin_name . '_field', 'posttype', 'position' ) == "65" ) ? ' selected' : '';?>><?php _e( '65 - Below Plugins', 'ptt-manager' );?></option>
                        <option value="70"<?php echo ( apply_filters( $this->plugin_name . '_field', 'posttype', 'position' ) == "70" ) ? ' selected' : '';?>><?php _e( '70 - Below Users', 'ptt-manager' );?></option>
                        <option value="75"<?php echo ( apply_filters( $this->plugin_name . '_field', 'posttype', 'position' ) == "75" ) ? ' selected' : '';?>><?php _e( '75 - Below Tools', 'ptt-manager' );?></option>
                        <option value="80"<?php echo ( apply_filters( $this->plugin_name . '_field', 'posttype', 'position' ) == "80" ) ? ' selected' : '';?>><?php _e( '80 - Below Settings', 'ptt-manager' );?></option>
                        <option value="100"<?php echo ( apply_filters( $this->plugin_name . '_field', 'posttype', 'position' ) == "100" ) ? ' selected' : '';?>><?php _e( '100 - Below last Separator', 'ptt-manager' );?></option>
                    </select>
        <br /><span class="description"><?php _e( 'Where to show the post type menu on the WP Admin Area Menu.', 'ptt-manager' );?></span></label></fieldset></td>
    </tr>
    <tr>
        <td class="td"><label><?php _e( 'Meta Boxes', 'ptt-manager' );?></label></td>
        <td><span class="description"><?php _e( 'Check to "exclude" support for meta boxes displayed on the editor (add/edit post type) page.', 'ptt-manager' );?></span><br /><br />
                <p><label for="supports-author"><input name="author" type="checkbox" id="supports-author" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'posttype', 'author' ), 1 );?>/><span class="description"><?php _e( 'Authors', 'ptt-manager' );?></span></label></p>
                <p><label for="supports-comments"><input name="comments" type="checkbox" id="supports-comments" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'posttype', 'comments' ), 1 );?>/><span class="description"><?php _e( 'Comments', 'ptt-manager' );?></span></label></p>
                <p><label for="supports-custom-fields"><input name="customfields" type="checkbox" id="supports-custom-fields" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'posttype', 'customfields' ), 1 );?>/><span class="description"><?php _e( 'Custom Fields', 'ptt-manager' );?></span></label></p>
                <p><label for="supports-editor"><input name="editor" type="checkbox" id="supports-editor" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'posttype', 'editor' ), 1 );?>/><span class="description"><?php _e( 'Editor', 'ptt-manager' );?></span></label></p>
                <p><label for="supports-excerpt"><input name="excerpt" type="checkbox" id="supports-excerpt" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'posttype', 'excerpt' ), 1 );?>/><span class="description"><?php _e( 'Excerpt', 'ptt-manager' );?></span></label></p>
                <p><label for="supports-page-attributes"><input name="pageattributes" type="checkbox" id="supports-page-attributes" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'posttype', 'pageattributes' ), 1 );?>/><span class="description"><?php _e( 'Page Attributes', 'ptt-manager' );?></span></label></p>
                <p><label for="supports-revisions"><input name="revisions" type="checkbox" id="supports-revisions" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'posttype', 'revisions' ), 1 );?>/><span class="description"><?php _e( 'Revisions', 'ptt-manager' );?></span></label></p>
                <p><label for="supports-post-formats"><input name="postformats" type="checkbox" id="supports-post-formats" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'posttype', 'postformats' ), 1 );?>/><span class="description"><?php _e( 'Post Formats', 'ptt-manager' );?></span></label></p>
                <p><label for="supports-thumbnail"><input name="thumbnail" type="checkbox" id="supports-thumbnail" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'posttype', 'thumbnail' ), 1 );?>/><span class="description"><?php _e( 'Thumbnails', 'ptt-manager' );?></span></label></p>
                <p><label for="supports-title"><input name="title" type="checkbox" id="supports-title" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'posttype', 'title' ), 1 );?>/><span class="description"><?php _e( 'Title', 'ptt-manager' );?></span></label></p>
                <p><label for="supports-trackbacks"><input name="trackbacks" type="checkbox" id="supports-trackbacks" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'posttype', 'trackbacks' ), 1 );?>/><span class="description"><?php _e( 'Trackbacks', 'ptt-manager' );?></span></label></p>
            </td>
    </tr>
    </table>

    <div class="textcenter"><?php submit_button();?></div>

    <?php if ( filter_input( INPUT_GET, "posttype" ) ) {?>
        <div class="left"><input type="checkbox" name="delete" value="1" /><?php _e( 'Delete This Record', 'ptt-manager' );?></div>
    <?php }?>

</form>
