<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }?>

<h3><?php _e( 'All Saved Options', 'ptt-manager' );?></h3>
<p><?php _e( 'Below is a listing of every setting saved by the Post Type & Taxonomy Manager plugin.', 'ptt-manager' );?></p>

<table class="form-table">
<tr>
  <th><?php _e( 'Option Name', 'ptt-manager' );?></th>
  <th><?php _e( 'Saved Setting', 'ptt-manager' );?></th>
</tr>
<tr>
<?php
    // Return Settings
    echo parent::settings();

    // No Settings
    if ( empty( parent::settings() ) ) {
        echo '<tr><td colspan="2"><p class="nosettings">' . __( 'No Saved Settings To View!', 'ptt-manager' ) . '</p></td></tr>';
    }
?>
<tr/>
</table>

<?php if ( ! empty( parent::settings() ) ) {?>
<br /><br /><br /><br /><hr />

<form enctype="multipart/form-data" method="post" action="">
<?php wp_nonce_field( $this->option_name . 'action', $this->option_name . 'nonce' );?>
<input type="hidden" name="type" value="deletesettings" />

    <div class="textcenter"><p class="submit"><input type="submit" name="submit" id="submit" class="button" value="Delete All Settings?" onclick="return confirm('Are you sure?');"></p></div>
    <div class="warning"><?php _e( 'Warning! This Can Not Be Undone!', 'ptt-manager' );?></div>

</form>

<br /><hr /><br />
<?php }
