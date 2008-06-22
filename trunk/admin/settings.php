<?php


// Display the content for the settings sub-panel - for admin users only!
function greatrealestate_admin_settings( ) {
	global $greatrealestate_db_version;
	if ( current_user_can( 'manage_options' ) ) {
		echo '<div class="wrap">';
		echo "<h2>Settings</h2>";

		// WARN, but don't fix... in case user upgraded while activated
		// (so the user can make necessary backups first)
		$installed_db_ver = get_option("greatrealestate_db_version");
		if (! $installed_db_ver == $greatrealestate_db_version ) {
			echo "<p>Installed $installed_db_ver - Required $greatrealestate_db_version</p>";
			echo "<h3>WARNING! You have a database version mismatch. If you recently upgraded this plugin, you must deactivate it and then reactivate it before using it. When you do so, the database will be upgraded for you automatically. (Do any necessary DB backups first). If you fail to do this, your WordPress installation may generate errors!</h3>";
		}
?>
<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>

<h3>Listings Features</h3>
<table class="form-table">
<tbody>

<tr valign="top">
<th scope="row"><?php _e('Main Listings Page','greatrealestate'); ?></th>
<td>
<?php printf(__('%s','greatrealestate'), wp_dropdown_pages("name=greatrealestate_pageforlistings&echo=0&show_option_none=".__('- Select -')."&selected=" . get_option('greatrealestate_pageforlistings'))); ?>
<br />
<?php _e('Select the Page to be used as the index to your Listings','greatrealestate') ?>
<br />
<?php _e('You will need to make all your Listings Pages subpages of this Page','greatrealestate') ?>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Listings Summary','greatrealestate'); ?></th>
<td>
<input id="genindex" type="checkbox" name="greatrealestate_genindex" value="true" <?php echo ( 'true' == get_option('greatrealestate_genindex') )  ? 'checked="checked"' : ''; ?> />
<br />
<?php _e('Check to generate a default summary of your listings on your Listings Page (uncheck if you are using a custom template for your index page)','greatrealestate') ?>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Individual Listings Pages','greatrealestate'); ?></th>
<td>
<input id="genlistpage" type="checkbox" name="greatrealestate_genlistpage" value="true" <?php echo ( 'true' == get_option('greatrealestate_genlistpage') )  ? 'checked="checked"' : ''; ?> />
<br />
<?php _e('Check to generate the default tabbed interface on each Listing Page (uncheck if you are using a custom template for your listings)','greatrealestate') ?>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Google API Key','greatrealestate'); ?></th>
<td>
<input id="googleapi" type="text" size="80" name="greatrealestate_googleAPIkey" value="<?php echo get_option('greatrealestate_googleAPIkey'); ?>" />
<br />
<?php _e('Paste your domain\'s <a title="get a Google API key" href="http://code.google.com/apis/maps/signup.html">Google API key</a> here to enable map displays for your listings','greatrealestate'); ?>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Maximum Listings Featured','greatrealestate'); ?></th>
<td>
<input id="maxfeatured" type="text" class="number" size="5" name="greatrealestate_maxfeatured" value="<?php echo get_option('greatrealestate_maxfeatured'); ?>" />
<br />
<?php _e('The default maximum number of featured listings, if not specified elsewhere','greatrealestate') ?>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Add Plugin CSS','greatrealestate'); ?></th>
<td>
<input id="usecss" type="checkbox" name="greatrealestate_usecss" value="true" <?php echo ( 'true' == get_option('greatrealestate_usecss') )  ? 'checked="checked"' : ''; ?> />
<br />
<?php _e('Check to use the plugin\'s default styling - uncheck if you have added your own style rules to your theme\'s <code>style.css</code> file (recommended)','greatrealestate') ?>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Enable "No Branding" option','greatrealestate'); ?></th> 
<td>
<input id="nobrand" type="checkbox" name="greatrealestate_nobrand" value="true" <?php echo ( 'true' == get_option('greatrealestate_nobrand') )  ? 'checked="checked"' : ''; ?> />
<br />
<?php _e('Check to allow <code>/nobrand/something/</code> to be added to a Page URL to remove navigation (Please copy <code>nobrand.css</code> to your theme directory and customize it)','greatrealestate') ?>
</td>
</tr>
</tbody>
</table>

<h3><?php _e('Listing Feeds (RSS)','greatrealestate'); ?></h3>
<p>To use the custom listing feed templates, see the user guide. You'll need to activate Feed Wrangler to work with the custom RSS stylesheets supplied.</p>
<table class="form-table">
<tbody>

<tr valign="top">
<th scope="row"><?php _e('Feed Title','greatrealestate'); ?></th>
<td>
<input id="feedtitle" type="text" size="60" name="greatrealestate_listfeedtitle" value="<?php echo get_option('greatrealestate_listfeedtitle'); ?>" />
<br />
<?php _e('e.g.: John Smith Listings, John Smith Homes For Sale, etc.','greatrealestate') ?>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Description','greatrealestate'); ?></th>
<td>
<input id="feeddesc" type="text" size="80" name="greatrealestate_listfeeddesc" value="<?php echo get_option('greatrealestate_listfeeddesc'); ?>" />
<br />
<?php _e('A snappy summary of what is in the listings feed','greatrealestate') ?>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Brokerage Name','greatrealestate'); ?></th>
<td>
<input id="broker" type="text" size="40" name="greatrealestate_broker" value="<?php echo get_option('greatrealestate_broker'); ?>" />
<br />
<?php _e('The name of the real estate brokerage holding the listings','greatrealestate') ?>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Agent Name','greatrealestate'); ?></th>
<td>
<input id="agent" type="text" size="40" name="greatrealestate_agent" value="<?php echo get_option('greatrealestate_agent'); ?>" />
<br />
<?php _e('The name of the listing real estate agent','greatrealestate') ?>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Agent Phone','greatrealestate'); ?></th>
<td>
<input id="phone" type="text" size="40" name="greatrealestate_agentphone" value="<?php echo get_option('greatrealestate_agentphone'); ?>" />
<br />
<?php _e('The listing real estate agent\'s phone number','greatrealestate') ?>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('MLS Name','greatrealestate'); ?></th>
<td>
<input id="mls" type="text" size="40" name="greatrealestate_mls" value="<?php echo get_option('greatrealestate_mls'); ?>" />
<br />
<?php _e('The name of the Multiple Listing Service','greatrealestate') ?>
</td>
</tr>

</tbody>
</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="greatrealestate_googleAPIkey,greatrealestate_listfeedtitle,greatrealestate_listfeeddesc,greatrealestate_broker,greatrealestate_agent,greatrealestate_mls,greatrealestate_maxfeatured,greatrealestate_usecss,greatrealestate_nobrand,greatrealestate_pageforlistings,greatrealestate_genindex,greatrealestate_genlistpage,greatrealestate_agentphone" />
<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options »') ?>" />
</p>
</form>
<?php
		//
		echo "</div>";
	}
}
?>
