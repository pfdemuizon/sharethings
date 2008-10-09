<?php

include_once 'client/facebook.php';
include_once 'lib.php';
include_once 'config.php';

$user = $_POST['fb_sig_user'];
$friends = explode(",", $_POST['fb_sig_friends']);
$item_id = $_GET['id'];
$item = get_shared_item($item_id);
$what = $item['what'];
$description = $item['description'];
$value = $item['value'];

$available = '';
$unavailable = ''; 
if ($item['available'] == 1)
	$available = 'selected="selected"'; 
else
	$unavailable = 'selected="selected"'; 

echo render_menu("my_listings", $user, $friends);
echo render_tabs(0);
?>

<div style="background-color:#EEEEEE">
	<h2 style="text-align:center;padding:20px 0 0 0">Edit Shared Item:</h2>
	<fb:editor action="<? echo $canvas_url; ?>/update.php?id=<? echo $item_id; ?>" labelwidth="150" width="300" align="left">
		<fb:editor-text label="Item to share (required)" name="what" value="<? echo $what; ?>"/>
		<fb:editor-textarea label="Description (required)" name="description"><? echo $description; ?></fb:editor-textarea>
		<fb:editor-text label="Estimated value (required)" name="value" value="<? echo $value; ?>"/>
		<fb:editor-custom label="Availability">
			<select name="available">
				<option value="0" <? echo $unavailable;?>>Unavailable</option>
				<option value="1" <? echo $available; ?>>Available</option>
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
