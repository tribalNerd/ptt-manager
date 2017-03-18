<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }?>

<h3><?php _e( 'Preset Post Types', 'ptt-manager' );?></h3>
<p><?php _e( 'Post Types are a unique form of "Posts" content, displayed below the Comments menu. Post Types can be used in conjunction with a related taxonomy, which provides categories under that post type.', 'ptt-manager' );?></p>
<p><?php _e( 'Dashicon: To use a custom icon, select the Icon button, then an icon, afterward scroll down and click the Save Changes button. To remove an icon, select the Icon button, press the < back link in the window, select a blank icon, then scroll down and click the Save Changes button.', 'ptt-manager' );?></p>

<form enctype="multipart/form-data" method="post" action="options.php">
<?php settings_fields( $this->plugin_name );?>
<?php do_settings_sections( $this->plugin_name );?>
<input type="hidden" name="type" value="preset-posttype" />

    <table class="form-table">
    <tr>
      <th><?php _e( 'Post Type Name and Description', 'ptt-manager' );?></th>
      <th class="td10 textcenter"><?php _e( 'Activate', 'ptt-manager' );?></th>
      <th class="td10 textcenter"><?php _e( 'Icon', 'ptt-manager' );?></th>
      <th class="td10 textcenter"><?php _e( 'Select', 'ptt-manager' );?></th>
    </tr>
    <tr>
        <td><label for="books"><b><?php _e( 'Books', 'ptt-manager' );?>:</b> <?php _e( 'Retail, display, review books.', 'ptt-manager' );?></label></th>
        <td class="td10 textcenter"><input name="books" type="checkbox" id="books" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'books' ), 1 );?>/></td>
        <td class="td10 textcenter"><span class="dashicons <?php echo apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'dashicons_picker_books' );?>"></span></td>
        <td class="td10 textcenter"><input id="dashicons_picker_books" name="dashicons_picker_books" type="hidden" value="<?php echo apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'dashicons_picker_books' );?>" /><input class="button dashicons-picker" type="button" value="Icon" data-target="#dashicons_picker_books" /></td>
    </tr><tr>
        <td><label for="docs"><b><?php _e( 'Docs', 'ptt-manager' );?>:</b> <?php _e( 'Display help/user documents.', 'ptt-manager' );?></label></th>
        <td class="td10 textcenter"><input name="docs" type="checkbox" id="docs" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'docs' ), 1 );?>/></td>
        <td class="td10 textcenter"><span class="dashicons <?php echo apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'dashicons_picker_docs' );?>"></span></td>
        <td class="td10 textcenter"><input id="dashicons_picker_docs" name="dashicons_picker_docs" type="hidden" value="<?php echo apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'dashicons_picker_docs' );?>" /><input class="button dashicons-picker" type="button" value="Icon" data-target="#dashicons_picker_docs" /></td>
    </tr><tr>
        <td><label for="faq"><b><?php _e( 'FAQ', 'ptt-manager' );?>:</b> <?php _e( 'Provide frequently asked questions.', 'ptt-manager' );?></label></th>
        <td class="td10 textcenter"><input name="faq" type="checkbox" id="faq" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'faq' ), 1 );?>/></td>
        <td class="td10 textcenter"><span class="dashicons <?php echo apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'dashicons_picker_faq' );?>"></span></td>
        <td class="td10 textcenter"><input id="dashicons_picker_faq" name="dashicons_picker_faq" type="hidden" value="<?php echo apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'dashicons_picker_faq' );?>" /><input class="button dashicons-picker" type="button" value="Icon" data-target="#dashicons_picker_faq" /></td>
    </tr><tr>
        <td><label for="music"><b><?php _e( 'Music', 'ptt-manager' );?>:</b> <?php _e( 'Selling, playing, downloading or displaying music.', 'ptt-manager' );?></label></th>
        <td class="td10 textcenter"><input name="music" type="checkbox" id="music" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'music' ), 1 );?>/></td>
        <td class="td10 textcenter"><span class="dashicons <?php echo apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'dashicons_picker_music' );?>"></span></td>
        <td class="td10 textcenter"><input id="dashicons_picker_music" name="dashicons_picker_music" type="hidden" value="<?php echo apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'dashicons_picker_music' );?>" /><input class="button dashicons-picker" type="button" value="Icon" data-target="#dashicons_picker_music" /></td>
    </tr><tr>
        <td><label for="portfolio"><b><?php _e( 'Portfolio', 'ptt-manager' );?>:</b> <?php _e( 'Showing off creatives, designs, photography and more.', 'ptt-manager' );?></label></th>
        <td class="td10 textcenter"><input name="portfolio" type="checkbox" id="portfolio" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'portfolio' ), 1 );?>/></td>
        <td class="td10 textcenter"><span class="dashicons <?php echo apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'dashicons_picker_portfolio' );?>"></span></td>
        <td class="td10 textcenter"><input id="dashicons_picker_portfolio" name="dashicons_picker_portfolio" type="hidden" value="<?php echo apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'dashicons_picker_portfolio' );?>" /><input class="button dashicons-picker" type="button" value="Icon" data-target="#dashicons_picker_portfolio" /></td>
    </tr><tr>
        <td><label for="s"><b><?php _e( 'Teams', 'ptt-manager' );?>:</b> <?php _e( 'Display your company/department team members.', 'ptt-manager' );?></label></th>
        <td class="td10 textcenter"><input name="teams" type="checkbox" id="teams" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'teams' ), 1 );?>/></td>
        <td class="td10 textcenter"><span class="dashicons <?php echo apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'dashicons_picker_teams' );?>"></span></td>
        <td class="td10 textcenter"><input id="dashicons_picker_teams" name="dashicons_picker_teams" type="hidden" value="<?php echo apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'dashicons_picker_teams' );?>" /><input class="button dashicons-picker" type="button" value="Icon" data-target="#dashicons_picker_teams" /></td>
    </tr><tr>
        <td><label for="testimonials"><b><?php _e( 'Testimonials', 'ptt-manager' );?>:</b> <?php _e( 'Testimonials from your visitors, clients or customers.', 'ptt-manager' );?></label></th>
        <td class="td10 textcenter"><input name="testimonials" type="checkbox" id="testimonials" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'testimonials' ), 1 );?>/></td>
        <td class="td10 textcenter"><span class="dashicons <?php echo apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'dashicons_picker_testimonials' );?>"></span></td>
        <td class="td10 textcenter"><input id="dashicons_picker_testimonials" name="dashicons_picker_testimonials" type="hidden" value="<?php echo apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'dashicons_picker_testimonials' );?>" /><input class="button dashicons-picker" type="button" value="Icon" data-target="#dashicons_picker_testimonials" /></td>
    </tr><tr>
        <td><label for="videos"><b><?php _e( 'Videos', 'ptt-manager' );?>:</b> <?php _e( 'Provide videos to your visitors.', 'ptt-manager' );?></label></th>
        <td class="td10 textcenter"><input name="videos" type="checkbox" id="videos" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'videos' ), 1 );?>/></td>
        <td class="td10 textcenter"><span class="dashicons <?php echo apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'dashicons_picker_videos' );?>"></span></td>
        <td class="td10 textcenter"><input id="dashicons_picker_videos" name="dashicons_picker_videos" type="hidden" value="<?php echo apply_filters( $this->plugin_name . '_field', 'preset_posttypes', 'dashicons_picker_videos' );?>" /><input class="button dashicons-picker" type="button" value="Icon" data-target="#dashicons_picker_videos" /></td>
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
      <th><?php _e( 'Taxonomy Name and Description', 'ptt-manager' );?></th>
      <th class="td10 textcenter"><?php _e( 'Activate', 'ptt-manager' );?></th>
      <th class="td10 textcenter"><?php _e( 'Posts', 'ptt-manager' );?></th>
      <th class="td10 textcenter"><?php _e( 'Pages', 'ptt-manager' );?></th>
    </tr>
    <tr>
        <td><label for="books"><b><?php _e( 'Books', 'ptt-manager' );?>:</b> <?php _e( 'Categories for selling or displaying books.', 'ptt-manager' );?></label></td>
        <td class="td10 textcenter"><input name="books" type="checkbox" id="books" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'books' ), 1 );?>/></td>
        <td class="td10 textcenter"><input name="books-posts" type="checkbox" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'books-posts' ), 1 );?>/></td>
        <td class="td10 textcenter"><input name="books-pages" type="checkbox" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'books-pages' ), 1 );?>/></td>
    </tr><tr>
        <td><label for="docs"><b><?php _e( 'Docs', 'ptt-manager' );?>:</b> <?php _e( 'Categories for providing help/user documents.', 'ptt-manager' );?></label></td>
        <td class="td10 textcenter"><input name="docs" type="checkbox" id="docs" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'docs' ), 1 );?>/></td>
        <td class="td10 textcenter"><input name="docs-posts" type="checkbox" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'docs-posts' ), 1 );?>/></td>
        <td class="td10 textcenter"><input name="docs-pages" type="checkbox" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'docs-pages' ), 1 );?>/></td>
    </tr><tr>
        <td><label for="faq"><b><?php _e( 'F.A.Q.\'s', 'ptt-manager' );?>:</b> <?php _e( 'Categories for displaying frequently asked questions.', 'ptt-manager' );?></label></td>
        <td class="td10 textcenter"><input name="faq" type="checkbox" id="faq" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'faq' ), 1 );?>/></td>
        <td class="td10 textcenter"><input name="faq-posts" type="checkbox" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'faq-posts' ), 1 );?>/></td>
        <td class="td10 textcenter"><input name="faq-pages" type="checkbox" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'faq-pages' ), 1 );?>/></td>
    </tr><tr>
        <td><label for="music"><b><?php _e( 'Music', 'ptt-manager' );?>:</b> <?php _e( 'Categories for selling, playing, downloading or displaying music.', 'ptt-manager' );?></label></td>
        <td class="td10 textcenter"><input name="music" type="checkbox" id="music" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'pr"><eset_taxonomies', 'music' ), 1 );?>/></td>
        <td class="td10 textcenter"><input name="music-posts" type="checkbox" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'music-posts' ), 1 );?>/></td>
        <td class="td10 textcenter"><input name="music-pages" type="checkbox" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'music-pages' ), 1 );?>/></td>
    </tr><tr>
        <td><label for="portfolio"><b><?php _e( 'Portfolio', 'ptt-manager' );?>:</b> <?php _e( 'Categories for creative professionals and businesses.', 'ptt-manager' );?></label></td>
        <td class="td10 textcenter"><input name="portfolio" type="checkbox" id="portfolio" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'portfolio' ), 1 );?>/></td>
        <td class="td10 textcenter"><input name="portfolio-posts" type="checkbox" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'portfolio-posts' ), 1 );?>/></td>
        <td class="td10 textcenter"><input name="portfolio-pages" type="checkbox" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'portfolio-pages' ), 1 );?>/></td>
    </tr><tr>
        <td><label for="teams"><b><?php _e( 'Teams', 'ptt-manager' );?>:</b> <?php _e( 'Categories for displaying your team members.', 'ptt-manager' );?></label></td>
        <td class="td10 textcenter"><input name="teams" type="checkbox" id="teams" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'teams' ), 1 );?>/></td>
        <td class="td10 textcenter"><input name="teams-posts" type="checkbox" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'teams-posts' ), 1 );?>/></td>
        <td class="td10 textcenter"><input name="teams-pages" type="checkbox" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'teams-pages' ), 1 );?>/></td>
    </tr><tr>
        <td><label for="testimonials"><b><?php _e( 'Testimonials', 'ptt-manager' );?>:</b> <?php _e( 'Categories for testimonials to your visitors, clients or customers.', 'ptt-manager' );?></label></td>
        <td class="td10 textcenter"><input name="testimonials" type="checkbox" id="testimonials" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'testimonials' ), 1 );?>/></td>
        <td class="td10 textcenter"><input name="testimonials-posts" type="checkbox" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'testimonials-posts' ), 1 );?>/></td>
        <td class="td10 textcenter"><input name="testimonials-pages" type="checkbox" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'testimonials-pages' ), 1 );?>/></td>
    </tr><tr>
        <td><label for="videos"><b><?php _e( 'Videos', 'ptt-manager' );?>:</b> <?php _e( 'Categories for displaying videos to your visitors.', 'ptt-manager' );?></label></td>
        <td class="td10 textcenter"><input name="videos" type="checkbox" id="videos" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'videos' ), 1 );?>/></td>
        <td class="td10 textcenter"><input name="videos-posts" type="checkbox" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'videos-posts' ), 1 );?>/></td>
        <td class="td10 textcenter"><input name="videos-pages" type="checkbox" value="1" <?php checked( apply_filters( $this->plugin_name . '_field', 'preset_taxonomies', 'videos-pages' ), 1 );?>/></td>
    </tr>
    </table>

    <div class="textcenter"><?php submit_button( 'Save Preset Taxonomies' );?></div>

</form>
