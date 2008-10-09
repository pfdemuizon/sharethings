<?php

include_once 'client/facebook.php';
include_once 'lib.php';
include_once 'config.php';

$item_id=$_GET['id'];
$what=$_POST['what'];
$description=$_POST['description'];
$value=$_POST['value'];
$available = $_POST['available'];

edit_shared_item($item_id,$what,$description,$value,$available);
?>

<fb:redirect url="<? echo $canvas_url; ?>/my_listings.php" />

