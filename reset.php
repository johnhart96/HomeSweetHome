<?php
require 'inc/functions.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if( isset( $_GET['yes'] ) ) {
	// Delete DB
	$machines = $db->query( "DELETE FROM `machines`" );
	$apps = $db->query( "DELETE FROM `applications`" );
	
	// Delete
	$files = scandir( "data" );
	foreach( $files as $file ) {
		if( $file !== "." or $file !== ".." ) {
			$bang = explode( "." , $file );
			if( $bang[1] == "zip" or $bang[1] == "rdp" ) {
				unlink( "data/" . $file );
			}
		}
	}
}
?>
<!doctype html>
<html lang="en">
	<head>
		<?php
		require 'inc/header.php';
		?>
	</head>
	<body>
		<?php require 'inc/menu.php'; ?>
		<main role="main" class="container">
			<h1>Configuration</h1>
			<br />
			<p>Are you 100% sure you want to delete your configuration and reset?</p>
			<button class="btn btn-danger" onclick="window.location='reset.php?yes'">Yes</button>&nbsp;<button class="btn btn-success" onclick="window.location='index.php'">No</button>
		</main>
		<?php require 'inc/footer.php'; ?>
	</body>
</html>