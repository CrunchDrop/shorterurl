<?php
require("ShorterURL.php");
$ShorterURL = new ShorterURL();

// Handle short URL redirection
if($_GET['u']) {
	$full_url = $ShorterURL->retrieveURL( $_GET['u'] );
	
	if(!empty($full_url)) {
		$ShorterURL->log_hit( $_GET['u'] );
		header("Location: {$full_url}");
	} else {
		die("This URL does not exist;");
	}
}
?>