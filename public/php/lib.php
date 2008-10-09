<?php

// singleton class for single instance of Facebook object
class FB {
	public static $fb;
	public static function get($api_key = null, $secret = null) {
		if (self::$fb === null)
			$fb = new Facebook($api_key, $secret);
		return $fb;
	}
}

function get_db_conn() {
  $conn = mysql_connect($GLOBALS['db_ip'], $GLOBALS['db_user'], $GLOBALS['db_pass']);
  if (!$conn) die('Could not connect: ' . mysql_error());
  mysql_select_db($GLOBALS['db_name'], $conn);
  return $conn;
}

function add_shared_item($who, $what, $description, $value, $available) {
	$conn = get_db_conn();
	$sql = "INSERT INTO items (who, what, description, value, time, available)
				VALUES ('$who', '$what', '$description', '$value', 'time()', '$available')";
	mysql_query($sql, $conn) or die('Error, query failed: '.$sql);
}

function get_shared_item($item_id) {
	$conn = get_db_conn();
	$sql = "SELECT * FROM items WHERE item_id='$item_id'";
	$res = mysql_query($sql, $conn);
  	return mysql_fetch_assoc($res);
}

// return items sharer is willing to share with borrower
// if borrower=null, get all items shared by sharer
function get_shared_items($sharer, $borrower=null) {
	if ($borrower)
		$share_limit = get_share_limits($sharer, $borrower);
	$conn = get_db_conn();
	$sql = "SELECT * FROM items WHERE who='$sharer' ORDER BY item_id DESC";
	$res = mysql_query($sql, $conn);
	$shared_items = array();
	while ($row = mysql_fetch_assoc($res)) {
		if (($borrower == null) ||	(($row['value'] < $share_limit) && ($row['available'] == 1)))
			$shared_items[] = $row;						
	}
	return $shared_items;
}

function edit_shared_item($item_id, $what, $description, $value, $available) {
  $conn = get_db_conn();
  $sql = "UPDATE items SET what='$what', description='$description', "
		. "value='$value', available='$available' WHERE item_id='$item_id'";
  mysql_query($sql, $conn) or die('Error, query failed: '. $sql);
}

function remove_shared_item($item_id) {
  $conn = get_db_conn();
  $sql = "DELETE FROM items WHERE item_id='$item_id'";
  mysql_query($sql, $conn) or die('Error, query failed: '. $sql);
}

function get_num_shared_items($sharers, $borrower=null) {
	$num_shared_items = 0;
	foreach ($sharers as $sharer) {
		$shared_items = get_shared_items($sharer, $borrower);
		$num_shared_items += count($shared_items);
	}
	return $num_shared_items;
}

function add_share_limit($sharer, $borrower, $share_limit) {
	$conn = get_db_conn();
	$sql = "INSERT INTO trust (sharer, borrower, share_limit)
				VALUES ('$sharer', '$borrower', '$share_limit')
				ON DUPLICATE KEY UPDATE share_limit='$share_limit'";
  mysql_query($sql, $conn) or die('Error, query failed: '.$sql);
}

function get_share_limit($sharer) {
	$conn = get_db_conn();
	$sql = "SELECT * FROM trust WHERE sharer='$sharer' AND borrower='$borrower'";
	$res = mysql_query($sql, $conn);
	$row = mysql_fetch_assoc($res);
	return $row['share_limit'];
}

function get_share_limits($sharer) {
	$conn = get_db_conn();
	$sql = "SELECT * FROM trust WHERE sharer='$sharer'";
	$res = mysql_query($sql, $conn)  or die('Error, query failed: '.$sql);
	$share_limits = array();
	while ($row = mysql_fetch_assoc($res)) {
	  $share_limits[$row['borrower']] = $row['share_limit'];
	}
	return $share_limits;
}

function init_share_limits($sharer, $borrowers) {
	global $default_limit;
	$share_limits = get_share_limits($sharer);
	if (count($share_limits) == 0) {
		foreach ($borrowers as $borrower) {
			add_share_limit($sharer, $borrower, $SHARE_LIMIT_DEFAULT);
		}
	}
}

function update_share_limit($sharer, $borrower, $share_limit) {
  $conn = get_db_conn();
  $sql = "UPDATE trust SET share_limit='$share_limit' "
		. "WHERE sharer='$sharer', borrower='$borrower'";
  return mysql_query($sql, $conn);	
}

function render_menu($select, $user, $friends) {
	global $canvas_url;
	$menu = array (
		"index" => "My Friends' Shared Things",
		"my_listings" => "My Shared Things",
		"invite" => "Invite",
		"about" => "About"
	);
	
	// get shared items for this user
	$shared_items = get_shared_items($user);

	// add number items shared and emphasis to menu
	$menu["index"] .= ' (' . get_num_shared_items($friends, $user) . ')';
	$menu["my_listings"] .= ' (' . count($shared_items) . ')';
	$menu[$select] = "<b>" . $menu[$select] . "</b>";

return '<fb:dashboard>'
     . '<fb:action href="' . $canvas_url . '/index.php">' . $menu["index"] . '</fb:action>'
     . '<fb:action href="' . $canvas_url . '/my_listings.php">' . $menu["my_listings"] . '</fb:action>'
     . '<fb:action href="' . $canvas_url . '/invite.php">' . $menu["invite"] . '</fb:action>'
     . '<fb:action href="http://www.facebook.com/apps/application.php?id=2395910624">'  . $menu["about"] . '</fb:action>'
     . '</fb:dashboard>';
}

function render_tabs($select) {
	global $canvas_url;
	$selected_tab = array("false","false", "false");	
	$selected_tab[$select] = "true";

	return '<fb:tabs>\n'
	     . '<fb:tab_item href="' . $canvas_url . '/my_listings.php" title="Manage My Shared Things" selected="' . $selected_tab[0] . '"/>'
	     . '<fb:tab_item href="' . $canvas_url . '/add_listing.php" title="Share a new item" selected="' . $selected_tab[1] . '"/>'
	     . '<fb:tab_item href="' . $canvas_url . '/privacy.php" title="Privacy Settings" selected="'.$selected_tab[2].'"/>'
	     . '</fb:tabs>';
}

function dump_request($request) {
	foreach ($request as $key => $value)
		echo "_REQUEST: key=" . $key . " value=" . $value . "<br>";
}

