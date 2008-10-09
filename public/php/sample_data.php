<?php

include_once '../client/facebook.php';
include_once 'lib.php';
include_once 'config.php';

// initialize facebook app
$fb = FB::get($api_key, $secret);

$users = array (
	"patrice" => 517441138,
	"james" => 403902,
 	"greg" => 509984051,
	"josh" => 501799772
);

add_friend_limit($users["greg"], $users["patrice"], 1000);
add_friend_limit($users["josh"], $users["patrice"], 50);
add_friend_limit($users["james"], $users["patrice"], -1);

add_shared_item($users["patrice"], "Subaru Outback", "This thing is totally beat up.", 10000.00);
add_shared_item($users["patrice"], "Ruby On Rails Book", "Agile Web Development with Ruby on Rails.", 15.00);
add_shared_item($users["patrice"], "Snowboard", "10 year old snowboard; pretty beat up.", 150.00);
add_shared_item($users["patrice"], "Electic Guitar", "Epiphone Les Paul electric guitar.", 75.00);
add_shared_item($users["greg"], "Canadian Flag", "Oh Canada", 10.00);
add_shared_item($users["greg"], "Wig", "Day trader wig.", 70.00);
add_shared_item($users["greg"], "Wii", "Fun.", 300.00);
add_shared_item($users["greg"], "Motorcycle", "Fucking awesome...", 3000.00);
add_shared_item($users["james"], "PowerBook", "iPower.", 3000.00);
add_shared_item($users["james"], "Digital Camera", "This thing is rad.", 500.00);
add_shared_item($users["james"], "Break Dancing Mat", "Head spins.", 5.00);
add_shared_item($users["josh"], "Basketball", "Nothing but net.", 15.00);
add_shared_item($users["josh"], "Pre-amps", "Good for recording.", 700.00);
add_shared_item($users["josh"], "Jetta", "My ride.", 7000.00);
?>

<fb:redirect url="<? echo $canvas_url; ?>/index.php" />
