<?php
$macAddress = $_POST['macAddress'];
$command = "wakeonlan " . $macAddress;
$result =  shell_exec( $command );
$message = substr( $result , -18 );
$message = "Sending a wake up to " . $message;
header( "Location:index.php?message=" . $message );
?>