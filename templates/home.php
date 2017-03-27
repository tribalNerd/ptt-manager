<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }?>

<h3><?php _e( 'Preset Post Types', 'ptt-manager' );?></h3>
<p><?php _e( 'Use the pre-cretaed post types below to quickly create a custom post type, which can be used in conjunction with a related taxonomy, providing you with a way categorize post type content. You can edit activated post type presets within the Post Types tab above.', 'ptt-manager' );?></p>

<table class="form-table">
    <tr>
        <th><?php _e( 'Post Type Name and Description', 'ptt-manager' );?></th>
        <th class="td10 textcenter"><?php _e( 'Icon', 'ptt-manager' );?></th>
        <th class="td10 textcenter"><?php _e( 'Set/Edit', 'ptt-manager' );?></th>
    </tr>
    <?php
        // Preset Post Types
        echo parent::presetPosttype( 'books', 'book', __( 'Retail, display, review books.', 'ptt-manager' ) );
        echo parent::presetPosttype( 'docs', 'docs', __( 'Display help/user documents.', 'ptt-manager' ) );
        echo parent::presetPosttype( 'faq', 'faq', __( 'Provide frequently asked questions.', 'ptt-manager' ) );
        echo parent::presetPosttype( 'music', 'music', __( 'Selling, playing, downloading or displaying music.', 'ptt-manager' ) );
        echo parent::presetPosttype( 'portfolio', 'portfolio', __( 'Showing off creatives, designs, photography and more.', 'ptt-manager' ) );
        echo parent::presetPosttype( 'teams', 'team', __( 'Display your company/department team members.', 'ptt-manager' ) );
        echo parent::presetPosttype( 'testimonials', 'testimonial', __( 'Testimonials from your visitors, clients or customers.', 'ptt-manager' ) );
        echo parent::presetPosttype( 'videos', 'video', __( 'Provide videos to your visitors.', 'ptt-manager' ) );
    ?>
</table>

<br />

<h3><?php _e( 'Preset Taxonomies', 'ptt-manager' );?></h3>
<p><?php _e( 'Use the pre-created taxonomies below to quickly create a custom taxonomy. Assoicate the taxonomy with a custom preset post type, posts or pages. You can edit activated taxonomy presets within the Taxonomies tab above.', 'ptt-manager' );?></p>

<table class="form-table">
    <tr>
        <th><?php _e( 'Taxonomy Name and Description', 'ptt-manager' );?></th>
        <th class="td10 textcenter"><?php _e( 'Post Type', 'ptt-manager' );?></th>
        <th class="td10 textcenter"><?php _e( 'Posts', 'ptt-manager' );?></th>
        <th class="td10 textcenter"><?php _e( 'Pages', 'ptt-manager' );?></th>
        <th class="td10 textcenter"><?php _e( 'Set/Edit', 'ptt-manager' );?></th>
    </tr>
    <?php
        // Preset Post Types
        echo parent::presetTaxonomy( 'books', 'book', __( 'Categories for selling or displaying books.', 'ptt-manager' ) );
        echo parent::presetTaxonomy( 'docs', 'docs', __( 'Categories for providing help/user documents.', 'ptt-manager' ) );
        echo parent::presetTaxonomy( 'faq', 'faq', __( 'Categories for displaying frequently asked questions.', 'ptt-manager' ) );
        echo parent::presetTaxonomy( 'music', 'music', __( 'Categories for selling, playing, downloading or displaying music.', 'ptt-manager' ) );
        echo parent::presetTaxonomy( 'portfolio', 'portfolio', __( 'Categories for creative professionals and businesses.', 'ptt-manager' ) );
        echo parent::presetTaxonomy( 'teams', 'team', __( 'Categories for displaying your team members.', 'ptt-manager' ) );
        echo parent::presetTaxonomy( 'testimonials', 'testimonial', __( 'Categories for testimonials to your visitors, clients or customers.', 'ptt-manager' ) );
        echo parent::presetTaxonomy( 'videos', 'video', __( 'Categories for displaying videos to your visitors.', 'ptt-manager' ) );
    ?>
</table>
