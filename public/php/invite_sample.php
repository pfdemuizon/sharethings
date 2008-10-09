<?
// this defines some of your basic setup
include_once '../client/facebook.php';
include_once 'lib.php';
include_once 'config.php';

$user = $_REQUEST['fb_sig_user'];
$friends = $_REQUEST['fb_sig_friends'];

$fb = FB::get($api_key, $secret);
$friends = $fb->api_client->friends_get();

?>
<h2>Find out your Friends Plans!</h2>
<?
// show friends
if (count($_POST['contact_friend'])>0) {
    // send the messages out to this persons friends
    $user_details = $fb->api_client->fql_query("SELECT first_name, last_name FROM user WHERE uid=$user");

    $friends_list = implode(',', $_POST['contact_friend']);

    $fbml_content = $user_details[0]['first_name'].' '.$user_details[0]['last_name'].' would like to know your plans for the weekend... You can let '.$user_details[0]['first_name'].' know by adding your <fb:req-choice url="http://apps.facebook.com/plansfortheweekend/" label="Plans for the Weekend" /> to your profile.';

    $send_email_url = $fb->api_client->notifications_sendRequest($friends_list, 'invitation', $fbml_content,'http://www.plansfortheweekend.co.uk/images/plan_icon.gif', 1);

    if ($send_email_url!='') {
        $fb->redirect($send_email_url . '&next=' . urlencode('?to=' . $user) . '&canvas');
?>
        <p>Your friends have been asked, you'll have to check their profiles for the answers...</p>
<?
    } else {
?>
        <p style="color:darkred;font-weight:bold;">Oops that didn't work :(</p>
        <p>I'm afraid Facebook has put limits on the number of times you can send your friends notifications from any application, so to let them know about Plans for the Weekend, please use the 'share' link below. Thanks and sorry for the trouble.</p>
        <fb:share-button class="url" href="http://apps.facebook.com/plansfortheweekend/">
        <meta name="title" content="Leonidas in All of Us"/>
        </fb:share-button>
<?
    }

} else {
    // display the list of their friends to tell
    $friends = $fb->api_client->fql_query("SELECT uid, name, pic_square FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1=$user)");

    if (count($friends)>0) {
        // just to make sure they have some friends!
        ?>
        <p>Who would you like to ask?</p>
        <form method="post" id="contact_friends_form">
        <p><a href="?unselect_all=0">Select All</a> | <a href="?unselect_all=1">Unselect All</a></p>
        <div style="height:400px;overflow:auto;">
            <table>
            <tr valign="top">
            <?PHP

            $count=1;
            $checked = "checked=\"checked\"";
            if ($_GET['unselect_all']==1) {
                $checked = "";
            }

            foreach ($friends as $friend) {
                ?>
                <td><input type="checkbox" name="contact_friend[]" id="contact_friend_<?PHP echo $friend['uid']; ?>" <?PHP echo $checked; ?> value="<?PHP echo $friend['uid']; ?>" />&nbsp;<a href="http://www.facebook.com/profile.php?id=<?PHP echo $friend['uid']; ?>" target="_blank"><?PHP echo $friend['name']; ?></a>
                <?PHP
                if ($friend['pic_square']) {
                    ?>
                    <br/><a href="http://www.facebook.com/profile.php?id=<?PHP echo $friend['uid']; ?>" target="_blank"><img src="<?PHP echo $friend['pic_square']; ?>" alt="<?PHP echo $friend['name']; ?>" title="<?PHP echo $friend['name']; ?>" /></a>
                    <?PHP
                } else {
                    ?>
                    <br/><a href="http://www.facebook.com/profile.php?id=<?PHP echo $friend['uid']; ?>" target="_blank"><img src="http://www.plansfortheweekend.co.uk/images/avatar.jpg" alt="<?PHP echo $friend['name']; ?>" title="<?PHP echo $friend['name']; ?>" /></a>
                    <?PHP
                }
                ?>
                </td>
                <?PHP
                if ($count==4) {
                    ?>
                    </tr><tr valign="top">
                    <?PHP
                    $count=1;
                } else {
                    $count++;
                }
            }
            ?>
            </tr>
            </table>
        </div>
        <br/>
        <p>Click the button below to send your friends an email asking what they plan to do this weekend. They can then add this app to their profile if they want to tell you.</p>
        <input type="submit" class="button" name="submitbut" value="Ask my friends" />
        <input type="hidden" name="submitted" value="send_friends" />
        </form>
        <?PHP
    } else {
        ?>
        <p>Sorry you don't have any friends...</p>
        <?PHP
    }
}
?>