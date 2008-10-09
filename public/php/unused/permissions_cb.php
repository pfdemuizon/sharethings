<?php

include_once '../client/facebook.php';
include_once 'lib.php';
include_once 'config.php';

// can't use 0 for "no items" because $friend_limits[$friend_uid]
// returns nul=0 if $friend_uid does not exist; when friend doesn't 
// exist, we want drop-down set to default value; use escape-value=-1 
// to work-around nul=0.
$SHARE_NONE = -1;
$limit2text = array (
	"$SHARE_NONE" 	=> "no items", 		  
	"20"				=> "items under $20",
	"50"				=> "items under $50",  
	"100" 			=> "items under $100", 
	"200" 			=> "items under $200", 
	"500" 			=> "items under $500", 
	"1000" 			=> "items under $1000",
	"$SHARE_ALL" 	=> "all items", 		  
);

global $SHARE_LIMIT_DEFAULT;
$user=$_REQUEST['fb_sig_user'];
$fb = FB::get($api_key, $secret);

echo render_menu("my_listings", $user);
echo render_tabs(2);

$checked = "";
if (isset($_GET['check']) && ($_GET['check'] == "all"))
	$checked = "checked";

if (isset($_POST['share_limit'])) {
	if (isset($_POST['selected_friends'])) {
		$friends = $_POST['selected_friends'];
		$share_limit = $_POST['share_limit'];
		foreach ($friends as $friend)
			add_share_limit($user, $friend, $share_limit);
	} else {
		echo '<fb:error message="Must select at least one friend." />';
	}
}

$friends = $fb->api_client->friends_get();
$share_limits = get_share_limits($user);
?>

<div style="background-color: #EEEEEE;float:center;padding:10px 10px 10px 10px">
	Control which of your shared items will be visible to specific friends by
	setting a dollar limit on what items they can borrow from you.  You can even
	share nothing with friends you don't know very well.
	<br/><br/>
	By default friends are limited to items under $<? echo $SHARE_LIMIT_DEFAULT; ?>.
	<hr size=1 noshade />

<form action="<? echo $canvas_url; ?>/permissions_cb.php" method="post">
	<table style="background-color: #EEEEEE; border: 1px solid black; width:100%">
		<table style="background-color: solid gray; width:100%">
			<tr style="width:100%"><td>
				Share 
				<select name="share_limit">
<?
					foreach ($limit2text as $share_limit => $text)
						echo '<option value="' . $share_limit . '">' . $text. '</option>';
?>
				</select> with <b>selected</b> friends.
				<input type="submit" name="submit" value="Set">
			</td></tr>
			<tr><td>
				Select: <a href="<?echo $canvas_url;?>/permissions_cb.php?check=all">All</a>, 
						  <a href="<?echo $canvas_url;?>/permissions_cb.php?check=none">None</a>
			</td></tr>
		</table>
		<table style="width:100%">
<?
		$ncols = min(count($friends),2);
		for ($i=0; $i<count($friends); $i+=2) {
			echo '<tr>';
         for ($j=0; $j<$ncols; $j++) {
         	$friend_uid = $friends[$i+$j];
				echo '<td style="width:50%">';
				echo '<input type="checkbox" name="selected_friends[]" value='
				 	. $friend_uid . ' ' . $checked . ' />' ;
				echo 'Sharing <b>' . $limit2text[$share_limits[$friend_uid]] 
					. '</b> with <fb:name uid="' . $friend_uid . '"/>.';
				echo '</td>';
         }
			echo '</tr>';
		}
?>
		</table>
		<table style="width:100%">
			<tr><td>
				Select: <a href="<?echo $canvas_url;?>/permissions_cb.php?check=all">All</a>, 
						  <a href="<?echo $canvas_url;?>/permissions_cb.php?check=none">None</a>
			</td></tr>
		</table>
	</table>
</form>
</div>
