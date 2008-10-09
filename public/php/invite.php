<?
include_once 'client/facebook.php';
include_once 'lib.php';
include_once 'config.php';

$user = $_POST['fb_sig_user'];
$friends = explode(",", $_POST['fb_sig_friends']);

$checked = "";
if (isset($_GET['select_all']) && ($_GET['select_all'] == 1)) 
	$checked = "checked";
 
$fb = FB::get($api_key, $secret);
echo render_menu("invite", $user, $friends);

$appUsers = $fb->api_client->friends_getAppUsers(); 
$invite_friends = array_diff($friends, $appUsers);
?>

<div style="background-color:white;padding:0 40px 0 10px">
	<h1 style="margin-bottom:5px">Invite Your Friends to Share Their Things</h1>
	Once your friends have added the app, you will be able to check out 
	the things they are sharing by clicking on the My Friends' Shared Things 
	at the top of the page.
</div>

<?/* 
<fb:explanation>
	<fb:message>
		Limits on Invitations<br/>
		<div style="size:75%">
		Facebook enforces a limit on the number of invites you can send. It 
		is best to invite a few people at a time (less than 10), and then wait 
		a day or so before you invite more.
		</div>
	</fb:message>
</fb:explanation>
*/?>
<div style="background-color:#EEEEEE;border:1px solid black;width:85%;margin:20px">
	<form action="<? echo $canvas_url; ?>/" method="post">
		<div style="margin:10px">
		<table>
<?
			$ncols = 3; $i = 0;
			foreach ($invite_friends as $friend) {
				if (($i%$ncols) == 0) 
					echo '<tr>';
				echo '<td style="width:25%">';
				echo '<input type="checkbox" name="selected_friends[]" value='
				 	. $friend . ' ' . $checked . ' /><fb:name uid="' 
					. $friend . '"/></td>';			
				if ((($i%$ncols) == ($ncols-1)) ||
					($i == (count($invite_friends)-1)))
					echo '</tr>';
				$i++;
			}
?>
		</table>
		<br/><input type="submit" name="Invite" value="Invite">
		</div>
	</form>
</div>