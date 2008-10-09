<?php

include_once '../client/facebook.php';
include_once 'lib.php';
include_once 'config.php';

$item_id=$_GET['id'];
remove_shared_item($item_id);
?>

<fb:redirect url="<? echo $canvas_url; ?>/my_listings.php" />
