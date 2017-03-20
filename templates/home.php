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
        echo parent::presetPosttype( __( 'books', 'ptt-manager' ), __( 'book', 'ptt-manager' ), __( 'Retail, display, review books.', 'ptt-manager' ) );
        echo parent::presetPosttype( __( 'docs', 'ptt-manager' ), __( 'docs', 'ptt-manager' ), __( 'Display help/user documents.', 'ptt-manager' ) );
        echo parent::presetPosttype( __( 'faq', 'ptt-manager' ), __( 'faq', 'ptt-manager' ), __( 'Provide frequently asked questions.', 'ptt-manager' ) );
        echo parent::presetPosttype( __( 'music', 'ptt-manager' ), __( 'music', 'ptt-manager' ), __( 'Selling, playing, downloading or displaying music.', 'ptt-manager' ) );
        echo parent::presetPosttype( __( 'portfolio', 'ptt-manager' ), __( 'portfolio', 'ptt-manager' ), __( 'Showing off creatives, designs, photography and more.', 'ptt-manager' ) );
        echo parent::presetPosttype( __( 'teams', 'ptt-manager' ), __( 'team', 'ptt-manager' ), __( 'Display your company/department team members.', 'ptt-manager' ) );
        echo parent::presetPosttype( __( 'testimonials', 'ptt-manager' ), __( 'testimonial', 'ptt-manager' ), __( 'Testimonials from your visitors, clients or customers.', 'ptt-manager' ) );
        echo parent::presetPosttype( __( 'videos', 'ptt-manager' ), __( 'video', 'ptt-manager' ), __( 'Provide videos to your visitors.', 'ptt-manager' ) );
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
        echo parent::presetTaxonomy( __( 'books', 'ptt-manager' ), __( 'book', 'ptt-manager' ), __( 'Categories for selling or displaying books.', 'ptt-manager' ) );
        echo parent::presetTaxonomy( __( 'docs', 'ptt-manager' ), __( 'docs', 'ptt-manager' ), __( 'Categories for providing help/user documents.', 'ptt-manager' ) );
        echo parent::presetTaxonomy( __( 'faq', 'ptt-manager' ), __( 'faq', 'ptt-manager' ), __( 'Categories for displaying frequently asked questions.', 'ptt-manager' ) );
        echo parent::presetTaxonomy( __( 'music', 'ptt-manager' ), __( 'music', 'ptt-manager' ), __( 'Categories for selling, playing, downloading or displaying music.', 'ptt-manager' ) );
        echo parent::presetTaxonomy( __( 'portfolio', 'ptt-manager' ), __( 'portfolio', 'ptt-manager' ), __( 'Categories for creative professionals and businesses.', 'ptt-manager' ) );
        echo parent::presetTaxonomy( __( 'teams', 'ptt-manager' ), __( 'team', 'ptt-manager' ), __( 'Categories for displaying your team members.', 'ptt-manager' ) );
        echo parent::presetTaxonomy( __( 'testimonials', 'ptt-manager' ), __( 'testimonial', 'ptt-manager' ), __( 'Categories for testimonials to your visitors, clients or customers.', 'ptt-manager' ) );
        echo parent::presetTaxonomy( __( 'videos', 'ptt-manager' ), __( 'video', 'ptt-manager' ), __( 'Categories for displaying videos to your visitors.', 'ptt-manager' ) );
    ?>
</table>
