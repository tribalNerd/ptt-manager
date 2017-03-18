<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }?>

<h3><?php _e( 'Tools', 'ptt-manager' );?></h3>
<p><?php _e( 'Export / Import & PHP Output Tools for the Post Types & Taxonomies Manager WordPress Plugin.', 'ptt-manager' );?></p>

<hr />

<div class="wrap">
<div style="" class="wrap-left textcenter">
    <p><a href="#ei_posttypes" class="button button-primary linkbutton" id="ei_posttypes_link"><?php _e( 'Export / Import Post Types', 'ptt-manager' )?></a></p>
    <p><a href="#ei_taxonomies" class="button button-primary linkbutton" id="ei_taxonomies_link"><?php _e( 'Export / Import Taxonomies', 'ptt-manager' )?></a></p>
    <p><a href="#settings" class="button button-primary linkbutton" id="settings_link"><?php _e( 'List All Saved Settings', 'ptt-manager' )?></a></p>
</div>
<div style="" class="wrap-right textcenter">
    <p><a href="#php_posttypes" class="button button-primary linkbutton" id="php_posttypes_link"><?php _e( 'PHP Output Post Types', 'ptt-manager' )?></a></p>
    <p><a href="#php_taxonomies" class="button button-primary linkbutton" id="php_taxonomies_link"><?php _e( 'PHP Output Taxonomies', 'ptt-manager' )?></a></p>
    <p><a href="#templates" class="button button-primary linkbutton" id="templates_link"><?php _e( 'How To Use Within Templates', 'ptt-manager' )?></a></p>
</div>

<br class="clear"/>
</div>

<br />

<div id="ei_posttypes">
    <h3><?php _e( 'Export / Import Post Types', 'ptt-manager' );?></h3>
    <p><?php _e( 'Export and import post type data for the Post Types & Taxonomies Manager plugin. Export does not include presets! Importing will override saved Post Type data from this plugin only!', 'ptt-manager' );?></p>

    <form enctype="multipart/form-data" method="post" action="options.php">
    <?php settings_fields( $this->plugin_name );?>
    <?php do_settings_sections( $this->plugin_name );?>
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
</div>


<div id="ei_taxonomies">
    <h3><?php _e( 'Export / Import Taxonomies', 'ptt-manager' );?></h3>
    <p><?php _e( 'Export and import taxonomy data for the Post Types & Taxonomies Manager plugin. Export does not include presets! Importing will override saved Taxonomy data from this plugin only!', 'ptt-manager' );?></p>

    <form enctype="multipart/form-data" method="post" action="options.php">
    <?php settings_fields( $this->plugin_name );?>
    <?php do_settings_sections( $this->plugin_name );?>
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
</div>


<div id="php_posttypes">
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

    <form enctype="multipart/form-data" method="post" action="options.php">
    <?php settings_fields( $this->plugin_name );?>
    <?php do_settings_sections( $this->plugin_name );?>
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
</div>


<div id="php_taxonomies">
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

    <form enctype="multipart/form-data" method="post" action="options.php">
    <?php settings_fields( $this->plugin_name );?>
    <?php do_settings_sections( $this->plugin_name );?>
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
</div>


<div id="settings">
    <h3><?php _e( 'All Saved Options', 'ptt-manager' );?></h3>
    <p><?php _e( 'Below is a listing of every setting saved by the Post Type & Taxonomy Manager plugin.', 'ptt-manager' );?></p>

    <form>
        <?php
        // Return Settings
        echo parent::settings();

        // No Settings
        if ( ! $this->settings ) {
            echo '<p><b>No Saved Settings To View!</b></p>';
        }
        ?>
    </form>
</div>


<div id="templates">
    <h3><?php _e( 'Using Post Types Within Templates', 'ptt-manager' );?></h3>

    <p><b><?php _e( 'More Information' , 'ptt-manager' );?></b>: <a href="https://codex.wordpress.org/Post_Type_Templates" target="_blank" />https://codex.wordpress.org/Post_Type_Templates</a></p>

    <p><b><?php _e( 'Templates' , 'ptt-manager' );?></b>: <?php _e( 'Post types use the theme templates, archive.php, single.php or index.php as the default templates if no custom template has been defined. To define a custom post type template, simply include the post type name at the end of the file name, such as: single-<b>posttypename</b>.php and archive-<b>posttypename</b>.php. Use the single.php template as your base template to copy, as it works best for post types.' , 'ptt-manager' );?></p>
    
    <p><b><?php _e( 'Categories / Post Tags' , 'ptt-manager' );?></b>: <?php _e( 'If your template(s) display categories & post tags with the single/archive templates, they may not work (appear) if you have not selected Categories / Tags under "Taxonomy Support" within the Post Types tab. If your post type does not use categories and tags, then you will need to remove them from the custom post type template.' , 'ptt-manager' );?></p>
    
    <p><b><?php _e( 'Post Type Description' , 'ptt-manager' );?></b>: <?php _e( 'To display the post type description within a template, first create a custom post type template. Then modify the PHP snip below to include your custom post type name and add it where you would like the description to appear within the template.' , 'ptt-manager' );?></p>

    <p><b><?php _e( 'The example below will get the taxonomy description.' , 'ptt-manager' );?></b><br />
        <code>$obj = get_post_type_object( '<?php _e( 'your_post_type_name' , 'ptt-manager' );?>' );<br />echo esc_html( $obj->description );</code></p>
    
    <p><b><?php _e( 'Other Post Type Fields' , 'ptt-manager' );?></b></b>: <?php _e( 'The get_post_type_object() function allows you to retrieve saved post type information, it can be used to get to retrieve all post type settings for a unique post type, such as the post types singular or plural name.' , 'ptt-manager' );?></p>

    <p><b><?php _e( 'The example below will get the singular name, with the first letter capitalized.' , 'ptt-manager' );?></b><br />
        <code>$obj = get_post_type_object( '<?php _e( 'your_post_type_name' , 'ptt-manager' );?>' );<br />echo esc_html( ucfirst( $obj->labels->singular_name ) );</code></p>

    <p><b><?php _e( 'The example below will get the plural name, with the first letter capitalized.' , 'ptt-manager' );?></b><br />
        <code>$obj = get_post_type_object( '<?php _e( 'your_post_type_name' , 'ptt-manager' );?>' );<br />echo esc_html( ucfirst( $obj->labels->name ) );</code></p>

    <p><?php _e( 'The difference between the last two examples, and the first example, is the inclusion "labels" array being called within the echo statement.' , 'ptt-manager' );?></p>

    <p><b><?php _e( 'More Settings' , 'ptt-manager' );?></b>: <a href="https://codex.wordpress.org/Function_Reference/get_post_type_object" target="_blank" />https://codex.wordpress.org/Function_Reference/get_post_type_object</a></p>

    <hr />
    
    <h3><?php _e( 'Using Taxonomies Within Templates', 'ptt-manager' );?></h3>

    <p><b><?php _e( 'More Information' , 'ptt-manager' );?></b>: <a href="https://developer.wordpress.org/themes/template-files-section/taxonomy-templates/" target="_blank" />https://developer.wordpress.org/themes/template-files-section/taxonomy-templates/</a></p>
 
    <p><b><?php _e( 'Templates' , 'ptt-manager' );?></b>: <?php _e( 'Taxonomies use the category.php, tag.php, and taxonomy.php templates, as the default templates if no custom template has been defined. To define a custom taxonomy template, simply include the taxonomy name at the end of the file name, such as: category-<b>posttypename</b>.php and taxonomy-<b>posttypename</b>.php. Most themes do not have a taxonomy.php template, however in my opinion it is the best template to create/use for all taxonomies. Use the categories.php template as your base template to copy, as it works best for taxonomies.' , 'ptt-manager' );?></p>

    <p><b><?php _e( 'PHP Code Snips' , 'ptt-manager' );?></b>: <?php _e( 'Outside of the basic usage (shown below) manipulating taxonomy data for display is not easy, and not the same depending on your needs. It is also outside the scope of these instructions.', 'ptt-manager' );?></p>

    <p><b><?php _e( 'Taxonomy Description' , 'ptt-manager' );?></b>: <?php _e( 'To display the taxonomy description within a template, first create a custom taxonomy template. Then modify the PHP snip below to include your custom taxonomy name and add it where you would like the description to appear within the template.' , 'ptt-manager' );?></p>

    <p><b><?php _e( 'The example below will get the taxonomy description.' , 'ptt-manager' );?></b><br />
        <code>$obj = get_taxonomy( '<?php _e( 'your_taxonomy_name' , 'ptt-manager' );?>' );<br />echo esc_html( $obj->description );</code></p>

    <p><b><?php _e( 'The example below will get the singular name of the taxonomy, with the first letter capitalized.' , 'ptt-manager' );?></b><br />
        <code>$obj = get_taxonomy( '<?php _e( 'your_taxonomy_name' , 'ptt-manager' );?>' );<br />echo esc_html( ucfirst( $obj->labels->singular_name ) );</code></p>

    <p><b><?php _e( 'The example below will get the plural name of the taxonomy, with the first letter capitalized.' , 'ptt-manager' );?></b><br />
        <code>$obj = get_taxonomy( '<?php _e( 'your_taxonomy_name' , 'ptt-manager' );?>' );<br />echo esc_html( ucfirst( $obj->labels->name ) );</code></p>

    <p><?php _e( 'The difference between the last two examples, and the first example, is the inclusion "labels" array being called within the echo statement.' , 'ptt-manager' );?></p>

    <p><b><?php _e( 'More Settings' , 'ptt-manager' );?></b>: <a href="https://codex.wordpress.org/Function_Reference/get_taxonomy" target="_blank" />https://codex.wordpress.org/Function_Reference/get_taxonomy</a></p>

    <hr />
    
    <p><?php _e( 'If you get stuck, or wish to display additional information and are unable to figure it out, Google resources like Stack Exchange using the search term: "<a href="https://www.google.com/search?q=site%3Astackexchange.com+wordpress+taxonomy+terms" target="_blank">site:stackexchange.com wordpress taxonomy terms</a>" with whatever your question is. Chances are, someone else has asked the same question as you, and some other nice person has provided an answer.' , 'ptt-manager' );?></p>
</div>

