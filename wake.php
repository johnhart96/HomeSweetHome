<?php
require 'inc/functions.php';
$machine = $_GET['machine'];
$getMachine = $db->query( "SELECT `mac`,`name` FROM `machines` WHERE `id` ='$machine' LIMIT 1" );
while( $row = $getMachine->fetchArray() ) {
	$macAddress = $row['mac'];
	$name = $row['name'];
}


$command = "wakeonlan " . $macAddress;
$result =  shell_exec( $command );
$message = "Sending a wake up to " . $name ;
header( "Location:index.php?message=" . $message );
?>