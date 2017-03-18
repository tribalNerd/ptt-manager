<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }?>

<h3><?php _e( 'Using Within Templates', 'ptt-manager' );?></h3>
<p><?php _e( 'How to use Post Types & Taxonomies within theme templates.', 'ptt-manager' );?></p>

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
