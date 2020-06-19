<?php
	require 'inc/functions.php';

	function returnMsg($message) {
		header( "Location:index.php?message=" . $message );
		return;
	}

	$id = filter_var($_GET['machine'], FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
	if (!$id) {
		returnMsg("Invalid machine ID");
		return;
	}

	$machine = $db->querySingle("SELECT `mac`,`name` FROM `machines` WHERE `id` ='$id'", true);
	if (!$machine) {
		returnMsg("Unknown machine ID");
		return;
	}
	if (empty( $getMachine['name'])) {
		$getMachine['name'] = $machine['mac'];
	}

	$message = "Sending a wake up to " . $getMachine['name'];
	if (!wol($machine['mac'])) {
		$message .= " FAILED";
	}

	returnMsg($message);
?>
