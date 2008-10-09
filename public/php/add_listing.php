<?php

include_once 'client/facebook.php';
include_once 'lib.php';
include_once 'config.php';

$fb = FB::get($api_key, $secret);
$friends = $fb->api_client->friends_get();

$user = $_POST['fb_sig_user'];
$friends = explode(",", $_POST['fb_sig_friends']);
$shared_items = get_shared_items($user);

echo render_menu("my_listings", $user, $friends);
echo render_tabs(1);

/*
	<fb:editor-custom label="Photo">
		<input type="hidden" name="MAX_FILE_SIZE" value="300000" />
		<input type="file" id="photo" name="photo"/>
	</fb:editor-custom>
*/

?>

<div style="background-color: #EEEEEE;padding:10px 0 0 0">
	<fb:editor action="<? echo $canvas_url; ?>/my_listings.php" labelwidth="175" width="300" align="left">
		<fb:editor-text label="Item to share (required)" name="what" value=""/>
		<fb:editor-textarea label="Description (required)" name="description" value=""/>
		<fb:editor-text label="Estimated value (required for use with privacy settings)" name="value" value=""/>
		<fb:editor-custom label="Availability">
			<select name="available">
				<option value="true" selected="selected">Available</option>
				<option value="false">Unavailable</option>
			</select>
		</fb:editor-custom>
		<fb:editor-custom>
			<div style="color: #666; margin-bottom: 3px;">
				By sharing an item, you are agreeing to the 
				<a href="<? echo $canvas_url; ?>/guidelines.php" target="_blank">Share Things Guidelines</a>
			</div>
		</fb:editor-custom>	
		<fb:editor-buttonset>
			<fb:editor-button value="Save"/>
			<fb:editor-cancel value="Cancel" href="<? echo $canvas_url; ?>/my_listings.php" />
		</fb:editor-buttonset>
	</fb:editor>
</div>
