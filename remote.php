<?php
require 'inc/functions.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$machine = secureInput( $_GET['machine'] );
$machineDetails = $db->query( "SELECT * FROM `machines` WHERE `id` ='$machine' LIMIT 1" );
while( $row = $machineDetails->fetchArray() ) {
	$machineParent = $row['parent'];
	$machineName = $row['name'];
	$machineRemote_type = $row['remote_type'];
	$machineIP = $row['ip'];
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
			<h1>Remote Control</h1>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="index.php">Home</a></li>
					<?php
					if( (int)$machineParent !== 0 ) {
						$getParent = $db->query( "SELECT * FROM `machines` WHERE `id` ='$machineParent' LIMIT 1" );
						while( $parent = $getParent->fetchArray() ) {
							echo "<li class='breadcrumb-item'><a href='index.php?parent=" . $machineParent . "'>" . $parent['name'] . "</a></li>";
						}
					}
					?>
					<li class="breadcrumb-item" aria-current="page"><a href='index.php?parent=<?php echo $machine; ?>'><?php echo $machineName; ?></a></li>
					<li class="breadcrumb-item active" aria-current="page">Remote Control</li>
				</ol>
			</nav>
			<div class="card">
				<div class="card-header"><strong><?php echo $machineName; ?></strong></div>
				<div class="card-body">
					<?php
					// Make Remote FIle
					switch( $machineRemote_type ) {
						case 0:
							rdp( $machineName , $machineIP );
							echo "<a download class='btn btn-success' href='data/" . $machineName . ".rdp' target='_new'>Start RDP</a>";
							break;
						case 1:
							vnc( $machineName , $machineIP );
							echo "<a download class='btn btn-success' href='data/" . $machineName . ".vnc' target='_new'>Start VNC</a>";
							break;
						case 2:
							// SSH
							break;
					}
					?>
				</div>
			</div>
		</main>
		<?php require 'inc/footer.php'; ?>
	</body>
</html>