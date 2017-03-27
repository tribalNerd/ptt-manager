<?php
if( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }?>

<div class="postbox">
    <h3><span><?php echo PTT_MANAGER_PAGE_NAME;?></span></h3>
<div class="inside" style="clear:both;padding-top:1px;"><div class="para">

    <ul>
        <li>&bull; <a href="https://github.com/tribalNerd/ptt-manager" target="_blank"><?php _e( 'Plugin Home Page', 'ptt-manager' );?></a></li>
        <li>&bull; <a href="https://github.com/tribalNerd/ptt-manager/issues" target="_blank"><?php _e( 'Bugs & Feature Request', 'ptt-manager' );?></a></li>
        <li>&bull; <a href="http://technerdia.com/help/" target="_blank"><?php _e( 'Contact Support', 'ptt-manager' );?></a></li>
        <li>&bull; <a href="http://technerdia.com/feedback/" target="_blank"><?php _e( 'Submit Feedback', 'ptt-manager' );?></a></li>
        <li>&bull; <a href="http://technerdia.com/projects/" target="_blank"><?php _e( 'More Plugins!', 'ptt-manager' );?></a></li>
    </ul>

</div></div> <!-- end inside-pad & inside -->
</div> <!-- end postbox -->

<p><a href="https://wordpress.org/plugins/ptt-manager/" target="_blank"><img src="<?php echo PTT_MANAGER_BASE_URL;?>/wp-content/plugins/ptt-manager/assets/sidebar_rate-plugin.gif" alt="<?php _e( 'Please Rate This Plugin At Wordpress.org!', 'ptt-manager' );?>" /></a></p>
