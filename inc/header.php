<!--

	HomeSweetHome Build 1.0.0.10

--->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
<meta name="generator" content="Jekyll v4.0.1">
<title>Home-Sweet-Home</title>

<link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/starter-template/">

<!-- Bootstrap core CSS -->
<link href="css/bootstrap.css" rel="stylesheet">

<style>
	.bd-placeholder-img {
		font-size: 1.125rem;
		text-anchor: middle;
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
	}
	
	@media (min-width: 768px) {
		.bd-placeholder-img-lg {
			font-size: 3.5rem;
		}
	}
</style>
<!-- Custom styles for this template -->
<link href="css/starter-template.css" rel="stylesheet">


<?php
// Error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Check valid user
$current_user = $_SERVER['PHP_AUTH_USER'];
define( "current_user" , $current_user ); 
if( empty( current_user ) ) {
	$path = shell_exec( "pwd" );
	die( "Athentication Error, perhaps your .htaccess file is missing or incorrect? check " . $path . "/.htaccess" );
}
// Check for .htaccess
if( ! file_exists( ".htaccess" ) ) {
	$path = shell_exec( "pwd" );
	die( "Athentication Error, perhaps your .htaccess file is missing or incorrect? check " . $path . "/.htaccess" );
}
?>