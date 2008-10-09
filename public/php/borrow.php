<?php

include_once '../client/facebook.php';
include_once 'lib.php';
include_once 'config.php';

$friend = $_GET['id'];
$item_id = $_GET['item_id'];
$item = get_shared_item($item_id);
$what = $item['what'];

// Send notification email
$fb = FB::get($api_key, $secret);
$send_email_url =
	$fb->api_client->notifications_send($friend, 
		'<fb:notif-subject>Request to borrow...</fb:notif-subject>' .
		'<fb:userlink uid="$user" shownetwork=false /> would like to borrow your $what.' .
		'<a href="$canvas_url\accept.php?id=$item_id">Accept</a> ' .
		'<a href="$canvas_url\decline.php?id=$item_id">Decline</a>',
		false);

$fb->redirect($send_email_url);

//if (isset($send_email_url) && $send_email_url) {
//	$fb->redirect($send_email_url);
//}


/*

$now = array("1043010313");
$linktext = "test";
$notifbody = "<fb:notif-subject>Share Things Message</fb:notif-subject>"
	. "<fb:userlink uid=\"$user\" shownetwork=false /> has invited you ...>";
$logourl = "http://farm1.static.flickr.com/7/5895836_4fee524519_t.jpg";
$url = $fb->api_client->notifications_send($now, $linktext, $notifbody, $logourl, true);
echo "<fb:iframe src=\"".$url."&next=done.php?\"></fb:iframe>";


$fbml = "<fb:notif-subject>Share Things Message</fb:notif-subject>";
$fb->$api_client->notifications_send($fbml);

?>

<?/*
<div style="float:left;background-color: #EEEEEE;padding:10px 0 0 0">
	<h2 style="text-align:center">Ask to borrow <fb:name uid="<? echo $friend; ?>" firstnameonly="true" possessive="true"/> <? echo $item['what']; ?></h2>
	<fb:success>
		<fb:message>Your shared item has been added</fb:message>
	</fb:success>
	<fb:editor action="<? echo $canvas_url; ?>/insert.php" labelwidth="175" width="300" style="float:left">
		<fb:editor-text label="Subject" name="subject" value="Request to borrow your <? echo $item['what']; ?>"/>
		<fb:editor-date label="Borrow Date" name="start_date" value="<? echo time(); ?>" />
		<fb:editor-time label="Time" name="start_time" value="10"/>
		<fb:editor-divider />
		<fb:editor-date label="Return Date" name="end_date" value="<? echo time(); ?>" />
		<fb:editor-time label="Time" name="end_time" value="10" />
		<fb:editor-textarea label="Message" name="message" value=""/>
		<fb:editor-buttonset>
			<fb:editor-button value="Send"/>
			<fb:editor-cancel value="Cancel" href="<? echo $canvas_url; ?>/index.php" />
		</fb:editor-buttonset>
	</fb:editor>	
</div>
*/?>