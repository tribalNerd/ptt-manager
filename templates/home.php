<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }?>

<?php
        foreach( wp_load_alloptions() as $option => $value ) {
            if ( strpos( $option, 'ptt-manager' ) === 0 ) {
                delete_option( $option );
            }
        }         
// Get Preset Post Types
$posttype = get_option( $this->plugin_name . '_preset_posttypes' );

// Get Preset Taxonomies
$taxonomy = get_option( $this->plugin_name . '_preset_taxonomies' );
?>

<h3><?php _e( 'Preset Post Types', 'ptt-manager' );?></h3>
<p><?php _e( 'Post Types are a unique form of "Posts" content, displayed below the Comments menu. Post Types can be used in conjunction with a related taxonomy, which provides categories under that post type.', 'ptt-manager' );?></p>
<p><?php _e( 'Dashicon: To use a custom icon, select the Icon button, then an icon, afterward scroll down and click the Save Changes button. To remove an icon, select the Icon button, press the < back link in the window, select a blank icon, then scroll down and click the Save Changes button.', 'ptt-manager' );?></p>

<form enctype="multipart/form-data" method="post" action="options.php">
<?php settings_fields( $this->plugin_name );?>
<?php do_settings_sections( $this->plugin_name );?>
<input type="hidden" name="type" value="preset-posttype" />

    <table class="form-table">
    <tr>
      <th><?php _e( 'Post Type', 'ptt-manager' );?></th>
      <th><?php _e( 'Select To Use', 'ptt-manager' );?></th>
      <th class="textcenter"><?php _e( 'Dashicon', 'ptt-manager' );?></th>
    </tr>
    <tr>
        <td class="td"><label for="book"><?php _e( 'Books', 'ptt-manager' );?></label></th>
        <td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Book Post Type', 'ptt-manager' );?></span></legend>
            <label for="book"><input name="books" type="checkbox" id="book" value="1" <?php if( isset( $posttype['books'] ) ) { checked( $posttype['books'], 1 ); }?> /><?php _e( 'Book Post Type for selling or displaying books.', 'ptt-manager' );?></label></fieldset></td>
        <td class="td10"><span class="<?php if( isset( $posttype['dashicons_picker_book'] ) ) {?>dashicons <?php echo esc_attr( $posttype['dashicons_picker_book'] ); }?>"></span><input id="dashicons_picker_book" name="dashicons_picker_book" type="hidden" /><input class="button dashicons-picker" type="button" value="Icon" data-target="#dashicons_picker_book" /></td>
    </tr><tr>
        <td class="td"><label for="docs"><?php _e( 'Docs', 'ptt-manager' );?></label></th>
        <td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Docs Post Type', 'ptt-manager' );?></span></legend>
            <label for="docs"><input name="docs" type="checkbox" id="docs" value="1" <?php if( isset( $posttype['docs'] ) ) { checked( $posttype['docs'], 1 ); }?> /><?php _e( 'Docs Post Type for display help/user documents.', 'ptt-manager' );?></label></fieldset></td>
        <td class="td10"><span class="<?php if( isset( $posttype['dashicons_picker_docs'] ) ) {?>dashicons <?php echo esc_attr( $posttype['dashicons_picker_docs'] ); }?>"></span><input id="dashicons_picker_docs" name="dashicons_picker_docs" type="hidden" /><input class="button dashicons-picker" type="button" value="Icon" data-target="#dashicons_picker_docs" /></td>
    </tr><tr>
        <td class="td"><label for="faq"><?php _e( 'F.A.Q.\'s', 'ptt-manager' );?></label></th>
        <td><fieldset><legend class="screen-reader-text"><span><?php _e( 'FAQ Post Type', 'ptt-manager' );?></span></legend>
            <label for="posttypefaq-"><input name="faq" type="checkbox" id="faq" value="1" <?php if( isset( $posttype['faq'] ) ) { checked( $posttype['faq'], 1 ); }?> /><?php _e( 'F.A.Q. Post Type for displaying frequently asked questions.', 'ptt-manager' );?></label></fieldset></td>
        <td class="td10"><span class="<?php if( isset( $posttype['dashicons_picker_faq'] ) ) {?>dashicons <?php echo esc_attr( $posttype['dashicons_picker_faq'] ); }?>"></span><input id="dashicons_picker_faq" name="dashicons_picker_faq" type="hidden" /><input class="button dashicons-picker" type="button" value="Icon" data-target="#dashicons_picker_faq" /></td>
    </tr><tr>
        <td class="td"><label for="music"><?php _e( 'Music', 'ptt-manager' );?></label></th>
        <td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Music Post Type', 'ptt-manager' );?></span></legend>
            <label for="music"><input name="music" type="checkbox" id="music" value="1" <?php if( isset( $posttype['music'] ) ) { checked( $posttype['music'], 1 ); }?> /><?php _e( 'Music Post Type for selling, playing, downloading or displaying music.', 'ptt-manager' );?></label></fieldset></td>
        <td class="td10"><span class="<?php if( isset( $posttype['dashicons_picker_music'] ) ) {?>dashicons <?php echo esc_attr( $posttype['dashicons_picker_music'] ); }?>"></span><input id="dashicons_picker_music" name="dashicons_picker_music" type="hidden" /><input class="button dashicons-picker" type="button" value="Icon" data-target="#dashicons_picker_music" /></td>
    </tr><tr>
        <td class="td"><label for="portfolio"><?php _e( 'Portfolio', 'ptt-manager' );?></label></th>
        <td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Portfolio Post Type', 'ptt-manager' );?></span></legend>
            <label for="portfolio"><input name="portfolio" type="checkbox" id="portfolio" value="1" <?php if( isset( $posttype['portfolio'] ) ) { checked( $posttype['portfolio'], 1 ); }?> /><?php _e( 'Portfolio Post Type for displaying creatives, designs, photography and more.', 'ptt-manager' );?></label></fieldset></td>
        <td class="td10"><span class="<?php if( isset( $posttype['dashicons_picker_portfolio'] ) ) {?>dashicons <?php echo esc_attr( $posttype['dashicons_picker_portfolio'] ); }?>"></span><input id="dashicons_picker_portfolio" name="dashicons_picker_portfolio" type="hidden" /><input class="button dashicons-picker" type="button" value="Icon" data-target="#dashicons_picker_portfolio" /></td>
    </tr><tr>
        <td class="td"><label for="team"><?php _e( 'Teams', 'ptt-manager' );?></label></th>
        <td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Team Post Type', 'ptt-manager' );?></span></legend>
            <label for="team"><input name="teams" type="checkbox" id="team" value="1" <?php if( isset( $posttype['teams'] ) ) { checked( $posttype['teams'], 1 ); }?> /><?php _e( 'Team Post Type for displaying your team members.', 'ptt-manager' );?></label></fieldset></td>
        <td class="td10"><span class="<?php if( isset( $posttype['dashicons_picker_team'] ) ) {?>dashicons <?php echo esc_attr( $posttype['dashicons_picker_team'] ); }?>"></span><input id="dashicons_picker_team" name="dashicons_picker_team" type="hidden" /><input class="button dashicons-picker" type="button" value="Icon" data-target="#dashicons_picker_team" /></td>
    </tr><tr>
        <td class="td"><label for="testimonial"><?php _e( 'Testimonials', 'ptt-manager' );?></label></th>
        <td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Testimonial Post Type', 'ptt-manager' );?></span></legend>
            <label for="testimonial"><input name="testimonials" type="checkbox" id="testimonial" value="1" <?php if( isset( $posttype['testimonials'] ) ) { checked( $posttype['testimonials'], 1 ); }?> /><?php _e( 'Testimonial Post Type for testimonials to your visitors, clients or customers.', 'ptt-manager' );?></label></fieldset></td>
        <td class="td10"><span class="<?php if( isset( $posttype['dashicons_picker_testimonial'] ) ) {?>dashicons <?php echo esc_attr( $posttype['dashicons_picker_testimonial'] ); }?>"></span><input id="dashicons_picker_testimonial" name="dashicons_picker_testimonial" type="hidden" /><input class="button dashicons-picker" type="button" value="Icon" data-target="#dashicons_picker_testimonial" /></td>
    </tr><tr>
        <td class="td"><label for="video"><?php _e( 'Videos', 'ptt-manager' );?></label></th>
        <td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Video Post Type', 'ptt-manager' );?></span></legend>
            <label for="video"><input name="videos" type="checkbox" id="video" value="1" <?php if( isset( $posttype['videos'] ) ) { checked( $posttype['videos'], 1 ); }?> /><?php _e( 'Video Post Type for displaying videos to your visitors.', 'ptt-manager' );?></label></fieldset></td>
        <td class="td10"><span class="<?php if( isset( $posttype['dashicons_picker_video'] ) ) {?>dashicons <?php echo esc_attr( $posttype['dashicons_picker_video'] ); }?>"></span><input id="dashicons_picker_video" name="dashicons_picker_video" type="hidden" /><input class="button dashicons-picker" type="button" value="Icon" data-target="#dashicons_picker_video" /></td>
    </tr>
    </table>

    <div class="textcenter"><?php submit_button( 'Save Preset Post Types' );?></div>

</form>


    <h3><?php _e( 'Preset Taxonomies', 'ptt-manager' );?></h3>
    <p><?php _e( 'Use taxonomies with either selected Post Types, or with your Posts or Pages, helping you better categorized your content. You do not have to select Posts/Pages if the taxonomy is being used with a Post Type.', 'ptt-manager' );?></p>

<form enctype="multipart/form-data" method="post" action="options.php">
<?php settings_fields( $this->plugin_name );?>
<?php do_settings_sections( $this->plugin_name );?>
<input type="hidden" name="type" value="preset-taxonomy" />

    <table class="form-table">
    <tr>
      <th><?php _e( 'Taxonomy', 'ptt-manager' );?></th>
      <th><?php _e( 'Select To Use', 'ptt-manager' );?></th>
      <th class="textcenter"><?php _e( 'Posts', 'ptt-manager' );?></th>
      <th class="textcenter"><?php _e( 'Pages', 'ptt-manager' );?></th>
    </tr>
    <tr>
        <td class="td"><label for="book"><?php _e( 'Books', 'ptt-manager' );?></label></td>
        <td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Book Taxonomy', 'ptt-manager' );?></span></legend>
            <label for="book"><input name="book" type="checkbox" id="book" value="1" <?php if( isset( $taxonomy['book'] ) ) { checked( $taxonomy['book'], 1 ); }?> /><?php _e( 'Book categories for selling or displaying books.', 'ptt-manager' );?></label></fieldset></td>
        <td class="textcenter td10"><input name="book-posts" type="checkbox" value="1" <?php if( isset( $taxonomy['book-posts'] ) ) { checked( $taxonomy['book-posts'], 1 ); }?> /></td>
        <td class="textcenter td10"><input name="book-pages" type="checkbox" value="1" <?php if( isset( $taxonomy['book-pages'] ) ) { checked( $taxonomy['book-pages'], 1 ); }?> /></td>
    </tr><tr>
        <td class="td"><label for="docs"><?php _e( 'Docs', 'ptt-manager' );?></label></td>
        <td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Docs Taxonomy', 'ptt-manager' );?></span></legend>
            <label for="docs"><input name="docs" type="checkbox" id="docs" value="1" <?php if( isset( $taxonomy['docs'] ) ) { checked( $taxonomy['docs'], 1 ); }?> /><?php _e( 'Docs categories for providing help/user documents.', 'ptt-manager' );?></label></fieldset></td>
        <td class="textcenter td10"><input name="docs-posts" type="checkbox" value="1" <?php if( isset( $taxonomy['docs-posts'] ) ) { checked( $taxonomy['docs-posts'], 1 ); }?> /></td>
        <td class="textcenter td10"><input name="docs-pages" type="checkbox" value="1" <?php if( isset( $taxonomy['docs-pages'] ) ) { checked( $taxonomy['docs-pages'], 1 ); }?> /></td>
    </tr><tr>
        <td class="td"><label for="faq"><?php _e( 'F.A.Q.\'s', 'ptt-manager' );?></label></td>
        <td><fieldset><legend class="screen-reader-text"><span><?php _e( 'FAQ Taxonomy', 'ptt-manager' );?></span></legend>
            <label for="faq"><input name="faq" type="checkbox" id="faq" value="1" <?php if( isset( $taxonomy['faq'] ) ) { checked( $taxonomy['faq'], 1 ); }?> /><?php _e( 'F.A.Q. categories for displaying frequently asked questions.', 'ptt-manager' );?></label></fieldset></td>
        <td class="textcenter td10"><input name="faq-posts" type="checkbox" value="1" <?php if( isset( $taxonomy['faq-posts'] ) ) { checked( $taxonomy['faq-posts'], 1 ); }?> /></td>
        <td class="textcenter td10"><input name="faq-pages" type="checkbox" value="1" <?php if( isset( $taxonomy['faq-pages'] ) ) { checked( $taxonomy['faq-pages'], 1 ); }?> /></td>
    </tr><tr>
        <td class="td"><label for="music"><?php _e( 'Music', 'ptt-manager' );?></label></td>
        <td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Music Taxonomy', 'ptt-manager' );?></span></legend>
            <label for="music"><input name="music" type="checkbox" id="music" value="1" <?php if( isset( $taxonomy['music'] ) ) { checked( $taxonomy['music'], 1 ); }?> /><?php _e( 'Music categories for selling, playing, downloading or displaying music.', 'ptt-manager' );?></label></fieldset></td>
        <td class="textcenter td10"><input name="music-posts" type="checkbox" value="1" <?php if( isset( $taxonomy['music-posts'] ) ) { checked( $taxonomy['music-posts'], 1 ); }?> /></td>
        <td class="textcenter td10"><input name="music-pages" type="checkbox" value="1" <?php if( isset( $taxonomy['music-pages'] ) ) { checked( $taxonomy['music-pages'], 1 ); }?> /></td>
    </tr><tr>
        <td class="td"><label for="portfolio"><?php _e( 'Portfolio', 'ptt-manager' );?></label></td>
        <td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Portfolio Taxonomy', 'ptt-manager' );?></span></legend>
            <label for="portfolio"><input name="portfolio" type="checkbox" id="portfolio" value="1" <?php if( isset( $taxonomy['portfolio'] ) ) { checked( $taxonomy['portfolio'], 1 ); }?> /><?php _e( 'Portfolio categories for creative professionals and businesses.', 'ptt-manager' );?></label></fieldset></td>
        <td class="textcenter td10"><input name="portfolio-posts" type="checkbox" value="1" <?php if( isset( $taxonomy['portfolio-posts'] ) ) { checked( $taxonomy['portfolio-posts'], 1 ); }?> /></td>
        <td class="textcenter td10"><input name="portfolio-pages" type="checkbox" value="1" <?php if( isset( $taxonomy['portfolio-pages'] ) ) { checked( $taxonomy['portfolio-pages'], 1 ); }?> /></td>
    </tr><tr>
        <td class="td"><label for="team"><?php _e( 'Teams', 'ptt-manager' );?></label></td>
        <td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Team Taxonomy', 'ptt-manager' );?></span></legend>
            <label for="team"><input name="team" type="checkbox" id="team" value="1" <?php if( isset( $taxonomy['team'] ) ) { checked( $taxonomy['team'], 1 ); }?> /><?php _e( 'Team categories for displaying your team members.', 'ptt-manager' );?></label></fieldset></td>
        <td class="textcenter td10"><input name="team-posts" type="checkbox" value="1" <?php if( isset( $taxonomy['team-posts'] ) ) { checked( $taxonomy['team-posts'], 1 ); }?> /></td>
        <td class="textcenter td10"><input name="team-pages" type="checkbox" value="1" <?php if( isset( $taxonomy['team-pages'] ) ) { checked( $taxonomy['team-pages'], 1 ); }?> /></td>
    </tr><tr>
        <td class="td"><label for="testimonial"><?php _e( 'Testimonials', 'ptt-manager' );?></label></td>
        <td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Testimonial Taxonomy', 'ptt-manager' );?></span></legend>
            <label for="testimonial"><input name="testimonial" type="checkbox" id="testimonial" value="1" <?php if( isset( $taxonomy['testimonial'] ) ) { checked( $taxonomy['testimonial'], 1 ); }?> /><?php _e( 'Testimonial categories for testimonials to your visitors, clients or customers.', 'ptt-manager' );?></label></fieldset></td>
        <td class="textcenter td10"><input name="testimonial-posts" type="checkbox" value="1" <?php if( isset( $taxonomy['testimonial-posts'] ) ) { checked( $taxonomy['testimonial-posts'], 1 ); }?> /></td>
        <td class="textcenter td10"><input name="testimonial-pages" type="checkbox" value="1" <?php if( isset( $taxonomy['testimonial-pages'] ) ) { checked( $taxonomy['testimonial-pages'], 1 ); }?> /></td>
    </tr><tr>
        <td class="td"><label for="video"><?php _e( 'Videos', 'ptt-manager' );?></label></td>
        <td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Video Taxonomy', 'ptt-manager' );?></span></legend>
            <label for="video"><input name="video" type="checkbox" id="video" value="1" <?php if( isset( $taxonomy['video'] ) ) { checked( $taxonomy['video'], 1 ); }?> /><?php _e( 'Video categories for displaying videos to your visitors.', 'ptt-manager' );?></label></fieldset></td>
        <td class="textcenter td10"><input name="video-posts" type="checkbox" value="1" <?php if( isset( $taxonomy['video-posts'] ) ) { checked( $taxonomy['video-posts'], 1 ); }?> /></td>
        <td class="textcenter td10"><input name="video-pages" type="checkbox" value="1" <?php if( isset( $taxonomy['video-pages'] ) ) { checked( $taxonomy['video-pages'], 1 ); }?> /></td>
    </tr>
    </table>

    <div class="textcenter"><?php submit_button( 'Save Preset Taxonomies' );?></div>

</form>
