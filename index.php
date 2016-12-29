<?php
	require("ShorterURL.php");
	$ShorterURL = new ShorterURL();
?>
<!DOCTYPE html>
<html>
<head>
	<title>ShorterURL</title>
<style>
	.form-control { width: 400px; }
</style>
</head>
<body>
<!-- Start Shorten URL form -->
<form action='' method='POST'>
	<div class="panel panel-default">
		<div class="panel-heading">URL to Shorten</div>
		<div class="panel-body">
			<div class="form-group">
				<div id='url'>
					<input type='text' name='url' placeholder="Enter a full URL prefixed with http:// to shorten " required="required" class='form-control' onclick='this.focus();this.select();' autofocus> 
				</div>
				<div id='alias'>
					<!-- Alias: <input type='text' name='alias' value='' class='form-control' /> -->
				</div>	
			</div>
			<?php
			if($_POST['url']) {
				$shortened_url = $ShorterURL->shortenURL( $_POST['url'] );
				
				if( $shortened_url ) {
					print "Your short url is: <input type='text' name='short_url' value='{$shortened_url}' onclick='this.focus();this.select();' class='form-control' />  ";
				} else {
					print "The URL was invalid, please try again and make sure it was entered correctly. ";
				}
			}
			?>
			<div id='buttons'>
				 <button type="submit" class="btn btn-default">Shorten!</button>  <button type="reset" class="btn btn-default">Clear</button>
			</div>

		</form>
	</div>
</div>
<!-- End Shorten URL form -->
</body>
</html>