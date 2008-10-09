<?

include_once '../client/facebook.php';
include_once 'lib.php';
include_once 'config.php';

// initialize facebook app
$fb = FB::get($api_key, $secret);
$fb->require_frame();
$user = $fb->require_login();

// render menu, 0="My friends' shared things" tab active
$friends = $fb->api_client->friends_get();
echo render_menu("index", $user, $friends);

// creates default share limits for 1st time users
init_share_limits($user, $friends);

$appUsers = $fb->api_client->friends_getAppUsers(); 
?>

<div style="background-color: white; padding: 0 40px 0 10px">
	<h1 style="margin-bottom: 5px"><a href="invite.php">Invite your friends</a> to share their things</h1>
	This page is for sharing things with your friends, but only <? echo count($appUsers); ?> of your friends have installed the "Share Things" 
	app. Go to the <a href="invite.php">invite page</a> to invite your friends to share things.
</div>

<?
// iterate through each friend of users's
foreach ($friends as $friend) {
	$shared_items = get_shared_items($friend, $user);
	if (count($shared_items) > 0) {
?>
		<div style="background-color: white; border: 1px solid black; margin: 10px">
			<table style="width: 100%">
				<tr>
					<td style="vertical-align: top; width: 0%" rowspan="5">
						<fb:profile-pic uid="<? echo $friend; ?>" linked="yes" />
					</td>
					<td style="width: 100%">
						<table style="width: 100%">
							<tr>
								<td style="padding: 4px; border-bottom: 0px solid lightgrey" colspan="4">
									<h2><fb:name uid="<? echo $friend; ?>" useyou="false"/></h2>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top; padding: 1px; background-color: #DDDDDD" colspan="4">
									<h4>Things <fb:name uid="<? echo $friend; ?>" firstnameonly="true"/> is sharing:</h4>
								</td>
							</tr>
<?
					// iterate through this friend's shared items
					foreach ($shared_items as $item) {
						$item_id = $item['item_id'];
						$what = $item['what'];
						$description = $item['description'];
						$value = $item['value'];
						
						// http://www.facebook.com/message.php?id=XXXXX&subject=XXXXX&msg=XXXXX
						$friend_name = $fb->api_client->users_getInfo($friend, 'name');
						$user_name = $fb->api_client->users_getInfo($user, 'name');
						
						$msg_url = "http://www.facebook.com/message.php"
							. "?id=" . $friend
							. "&amp;subject=ShareThings: Request to borrow..."
							. "&amp;msg=Hey " . $friend_name[0]['name'] . ", \n"
							. "I\'d like to borrow your " . strtolower($what) . ".\n"
							. "Thanks!\n" 
							. $user_name[0]['name'];
?>
						<tr><td colspan="5"><hr size="1" noborder/></td></tr>
						<tr>
							<td style="width: 20%" valign="top"><? echo $what; ?></td>
							<td style="width: 1%"/>
							<td style="width: 50%"><? echo $description; ?></td>
							<td style="width: 1%"/>
							<td style="width: 18%" valign="top">
								<a href="<? echo $msg_url; ?>">Ask to borrow</a>
							</td>
							<td style="width: 10%"/>
						</tr>
						
<?
					}
?>
						<tr><td colspan="5"><hr size="1" noborder/></td></tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
<?
	}
}
?>


