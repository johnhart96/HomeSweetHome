<?php
	require 'inc/functions.php';
	$machine = secureInput( $_GET['machine'] );
	$getMachine = $db->query( "SELECT `mac`,`name` FROM `machines` WHERE `id` ='$machine' LIMIT 1" );
	while( $row = $getMachine->fetchArray() ) {
		$macAddress = $row['mac'];
		$name = $row['name'];
		if (empty($name)) { $name = $macAddress; }
	}

	$message = "Sending a wake up to " . $name;
	if (!wol($macAddress)) {
		$message .= " FAILED";
	}

	header( "Location:index.php?message=" . $message );
?>
