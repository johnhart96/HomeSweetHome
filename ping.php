<?php
  require 'inc/functions.php';

  $ipaddr = filter_var($_GET['ipaddr'], FILTER_VALIDATE_IP);
  if (empty($ipaddr)) {
    echo -1;
    return;
  }

  if ( ping( $ipaddr ) ) {
    echo 1;
  } else {
    echo 0;
  }
?>
