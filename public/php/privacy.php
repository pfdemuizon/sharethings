<?php

include_once 'client/facebook.php';
include_once 'lib.php';
include_once 'config.php';

// can't use 0 for "no items" because $share_limits[$friend]
// returns nul=0 if $friend does not exist; when friend doesn't 
// exist, we want drop-down set to default value; use escape-value=-1 
// to work-around nul=0.
$SHARE_NONE = -1;
$lmt_drp_dwn = array (
	"$SHARE_NONE" 	=> "no items", 		  
	"20"				=> "items under $20",
	"50"				=> "items under $50",  
	"100" 			=> "items under $100", 
	"200" 			=> "items under $200", 
	"500" 			=> "items under $500", 
	"1000" 			=> "items under $1000",
	"$SHARE_ALL" 	=> "any item"		  
);

global $SHARE_LIMIT_DEFAULT;

$user = $_POST['fb_sig_user'];
$friends = explode(",", $_POST['fb_sig_friends']);

$share_limit_all = 0;
if (isset($_POST['friend_all']))
	$share_limit_all = $_POST['friend_all'];

$frnd_drpdwn = 'friend_' . $friends[0];
if (isset($_POST[$frnd_drpdwn])) {
	foreach ($friends as $friend) {
		$frnd_drpdwn = 'friend_' . $friend;
		$share_limit = $_POST[$frnd_drpdwn];
		add_share_limit($user, $friend, $share_limit);
	}	
	$render_msg = (bool)true;
}

echo render_menu("my_listings", $user, $friends);
echo render_tabs(2);

$share_limits = get_share_limits($user);
?>

<div style="background-color:#EEEEEE;float:center;padding:10px 10px 10px 10px">
	Control which of your shared items will be visible to your friends by
	setting a dollar limit on what items they can borrow from you.
	By default friends are limited to items under $<? echo $SHARE_LIMIT_DEFAULT; ?>.

<? if ($render_msg) { ?>
	<fb:success>
		<fb:message>Your privacy settings have been saved.</fb:message>
	</fb:success>
<? } ?>

<form action="<? echo $canvas_url ?>/privacy.php" method="post">
	<table style="margin:10px 0 0 0">
		<tr><td>Share 
		<select name="friend_all">
<?
			if ($share_limit_all != 0)
				$selected_limit = $share_limit_all;
			else
				$selected_limit = $SHARE_LIMIT_DEFAULT;

			foreach ($lmt_drp_dwn as $share_limit => $label) {
				if ($share_limit == $selected_limit) 
					$select = ' selected="selected"';
				else
					$select = '';
				echo '<option value="' . $share_limit . '"' . $select . '>' . $label. '</option>';						
			}
?>
		</select> with <b>all</b> friends.
		<input type="submit" name="set_all" value="Set all">
		</td></tr>
	</table>
	<hr size="1" noborder />
</form>
<form action="<? echo $canvas_url ?>/privacy.php" method="post">
	<table style="width:100%">
<?
			$ncols = 2; $i = 0;
			foreach ($share_limits as $friend => $share_limit) {
				if (($i%$ncols) == 0) 
					echo '<tr style="width:100%">';
	
				if ($share_limit_all != 0)
					$selected_limit = $share_limit_all;
				elseif ($share_limit != null)
					$selected_limit = $share_limit;
				else
					$selected_limit = $default_share_limit;
?>
         	<td style="width:30%">
         		Share 
         		<select name="friend_<? echo $friend; ?>">
<?						foreach ($lmt_drp_dwn as $lmt => $lbl) {
							if ($lmt == $selected_limit)
								$select = ' selected="selected"';
							else
								$select = '';
							echo '<option value="' . $lmt . '"' . $select . '>' . $lbl. '</option>';						
         			}
?>
         		</select>
         		with <fb:name uid="<?echo $friend; ?>"/> 
         	</td>
<?
				if ((($i%$ncols) == ($ncols-1)) ||
					($i == (count($invite_friends)-1)))
					echo '</tr>';
				$i++;
			}
?>
</table>
	<table style="width:100%">
		<tr>
			<td style="width:45%"/>
			<td style="width:10%">
				<input type="submit" name="submit" value="Save">
			</td>
			<td style="width:45%"/>
		</tr>
	</table>
</form>
</div>
