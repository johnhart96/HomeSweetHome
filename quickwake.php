<?php
  require 'inc/functions.php';

  $macAddress = filter_var($_POST['macAddress'], FILTER_VALIDATE_MAC);
  if (empty($macAddress)) {
    $message = "Invalid MAC address";
  } else {
    $message = "Sending a wake up to " . $macAddress;
    if (!wol($macAddress)) {
      $message .= " FAILED";
    }
  }

  header( "Location:index.php?message=" . $message );
?>
