<?php
if( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }?>

<div class="postbox">
    <h3><span><?php echo PTT_MANAGER_PAGE_NAME;?></span></h3>
<div class="inside" style="clear:both;padding-top:1px;"><div class="para">

	<?php echo $this->sidebar();?>

</div></div> <!-- end inside-pad & inside -->
</div> <!-- end postbox -->

<p><a href="https://wordpress.org/support/plugin/ptt-manager/reviews/?rate=5#new-post" target="_blank"><img src="<?php echo $this->base_url;?>/wp-content/plugins/ptt-manager/assets/sidebar_rate-plugin.gif" alt="<?php _e( 'Please Rate This Plugin At Wordpress.org!', 'ptt-manager' );?>" /></a></p>
