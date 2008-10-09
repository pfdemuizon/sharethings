<?php

include_once '../client/facebook.php';
include_once 'lib.php';
include_once 'config.php';

$fb = FB::get($api_key, $secret);
//$friends = $fb->api_client->friends_get();
$user=$_POST['fb_sig_user'];
$friends = explode(",", $_POST['fb_sig_friends']);

if (isset($_POST['what'])) {
	$who = $user;
	$what = $_POST['what'];
	$description = $_POST['description'];
	$value = $_POST['value'];
	$available = (bool)false;
	if ($_POST['available'] == 1)
		$available = (bool)true;
	add_shared_item($who,$what,$description,$value,$available);
	$render_msg = (bool)true;
}

echo render_menu("my_listings", $user, $friends);
echo render_tabs(0);

if ($render_msg) {
?>
	<fb:success>
		<fb:message>Your shared item has been added.</fb:message>
	</fb:success>
<?  
} 
?>

<div style="background-color: white; border: 1px solid black; margin: 10px">
	<table style="width: 100%">
		<tr><td style="vertical-align: top; width: 0%" rowspan="5">
				<fb:profile-pic uid="<? echo $user; ?>" linked="yes" />
			 </td>
			 <td style="width: 100%">
				<table style="width: 100%">
					<tr><td style="padding: 4px; border-bottom: 0px solid lightgrey" colspan="4">
							<h2><fb:name uid="<? echo $user; ?>" useyou="false"/></h2>
						 </td>
					</tr>
					<tr><td style="vertical-align: top; padding: 3px; background-color: #DDDDDD" colspan="4">
							<h4>Things you are willing to share with friends:</h4>
						 </td>
					</tr>
<?
				// iterate through shared items
				$shared_items = get_shared_items($user);
				foreach ($shared_items as $item) {
					$item_id = $item['item_id'];
					$what = $item['what'];
					$description = $item['description'];
					$value = $item['value'];
					if ($item['available'])
						$available = "Available";
					else
						$available = "Unavailable"
?>
					<tr><td colspan="9"><hr size="1" noborder/></td></tr>
					<tr valign="top">
						<td style="width: 20%"><? echo $what; ?></td>
						<td style="width: 1%"/>
						<td style="width: 40%"><? echo $description; ?></td>
						<td style="width: 1%"/>
						<td style="width: 8%">$<? echo $value; ?></td>
						<td style="width: 1%"/>
						<td style="width: 10%"><? echo $available; ?></td>
						<td style="width: 1%"/>
						<td style="width: 17%">
							<a href="<? echo $canvas_url; ?>/edit.php?id=<? echo $item_id; ?>">Edit</a><span class="pipe">|</span>
							<a href="<? echo $canvas_url; ?>/delete.php?id=<? echo $item_id; ?>">Remove</a>
						</td>
					</tr>
<?
				}
?>
					<tr><td colspan="9"><hr size="1" noborder/></td></tr>
				</table>
			</td>
		</tr>
	</table>
</div>
