<?php

include_once '../client/facebook.php';
include_once 'lib.php';
include_once 'config.php';

$user = $_REQUEST['fb_sig_user'];
$fb = FB::get($api_key, $secret);
$friends = $fb->api_client->friends_get();

foreach ($friends as $friend) {
	$frnd_drpdwn = 'friend_' . $friend;
	$share_limit = $_POST[$frnd_drpdwn];
	add_share_limit($user, $friend, $share_limit);
}

?>

<fb:redirect url="<? echo $canvas_url; ?>/permissions.php" />
