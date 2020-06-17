<?php require 'inc/functions.php'; ?>
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
			<?php
			if( isset( $_GET['message'] ) ) {
				echo "<div class='alert alert-info' role='alert'>";
				echo $_GET['message'];
				echo "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
    			echo "<span aria-hidden='true'>&times;</span>";
  				echo "</button>";
				echo "</div>";
				echo "<br />";
			}
			?>
			<h1>Welcome, <?php echo current_user; ?></h1>
			<?php
			echo "<nav aria-label='breadcrumb'><ol class='breadcrumb'>";
			echo "<li class='breadcrumb-item active'>Home</li>";
			if( ! isset( $_GET['parent'] ) ) {
				$getMachines = $db->query( "SELECT * FROM `machines` WHERE `parent` =0" );
				echo "<li class='breadcrumb-item active' aria-current='page'><a href='index.php'>Machines</a></li>";
			} else {
				$parent = $_GET['parent'];
				$getMachines = $db->query( "SELECT * FROM `machines` WHERE `parent` ='$parent'" );
				echo "<li class='breadcrumb-item active'><a href='index.php'>Machines</a></li>";
				$getParentDetails = $db->query( "SELECT * FROM `machines` WHERE `id` ='$parent' LIMIT 1" );
				while( $row = $getParentDetails->fetchArray() ) {
					$parentName = $row['name'];
					$parentIP = $row['ip'];
					$parentMac = $row['mac'];
					$parentParent = $row['parent'];
					$parentType = $row['type'];
				}
				// Is this a VM?
				if( (int)$parentType == 0 ) {
					echo "<li class='breadcrumb-item active' aria-current='page'><a href='index.php?id=" . $parent . "'>" . $parentName . "</a></li>";
				} else if( (int)$parentType == 1 ) {
					$getParentParent = $db->query( "SELECT * FROM `machines` WHERE `id` = '$parentParent' LIMIT 1" );
					while( $row = $getParentParent->fetchArray() ) {
						echo "<li class='breadcrumb-item active'><a href='index.php?id=" . $row['id'] . "'>" . $row['name'] . "</a></li>";
					}
					echo "<li class='breadcrumb-item active' aria-current='page'><a href='index.php?id=" . $parent . "'>" . $parentName . "</a></li>";
				}
			}

			echo "</ol></nav>";
			// Virtual Machine Host
			if( isset( $_GET['parent'] ) ) {
				echo "<div class='card'>";
				echo "<div class='card-header'><strong>" . $parentName . ":</strong></div>";
				echo "<div class='card-body'>";
				$ping = ping( $parentIP );
				if( $ping == 1 ) {
					echo "<div class='alert alert-success' style='padding: 0px; role='alert'><center>Online</center></div>";
				} else {
					echo "<div class='alert alert-danger' style='padding: 0px; role='alert'><center>Online</center></div>";
				}
				echo "<p>";
				echo "IP: Address: " . $parentIP . "<br />";
				echo "Mac Address: " . $parentMac . "<br />";
				echo "</p>";
				if( $ping == 1 ) {
					echo "<a href='remote.php?machine=" . $parent . "' class='btn btn-primary'>Remote Control</a>";
				} else {
					echo "<a href='wake.php?machine=" . $parent . "' class='btn btn-success'>Attempt Wake</a>";
				}
				echo "</div>"; // Card body
				echo "</div>"; // Card
			}
			echo "<div class='row'>";
			while( $machine = $getMachines->fetchArray() ) {
				echo "<div class='col-sm-6'>";
				echo "<div class='card'>";
				echo "<div class='card-body'>";
				echo "<h5 class='card-title'>" . $machine['name'] . "</h5>";
				$ping = ping( $machine['ip'] );
				if( $ping == 1 ) {
					echo "<div class='alert alert-success' style='padding: 0px; role='alert'>";
				} else {
					echo "<div class='alert alert-danger' style='padding: 0px; role='alert'>";
				}
				if( $ping == 1 ) {
					echo "<center>Online</center>";
				} else {
					echo "<center>Offline</center>";
				}
				echo "</div>"; // Alert
				echo "<p class='card-text'>";
				echo "IP Address: " . $machine['ip'] . "<br />";
				echo "Mac Address: " . $machine['mac'] . "<br />";
				echo "</p>";
				if( $ping == 1 ) {
					echo "<a href='remote.php?machine=" . $machine['id'] . "' class='btn btn-primary'>Remote Control</a>";
				} else {
					echo "<a href='wake.php?machine=" . $machine['id'] . "' class='btn btn-success'>Attempt Wake</a>";
				}
				// Check for VMs
				$machineID = $machine['id'];
				$getVMs = $db->query( "SELECT * FROM `machines` WHERE `parent` ='$machineID' " );
				$count = 0;
				while( $row = $getVMs->fetchArray() ) {
					$count ++;
				}
				if( $count !== 0 ) {
					echo "&nbsp;<a href='index.php?parent=" . $machineID . "' class='btn btn-info'>Virtual Machines (" . $count . ")</a>";
				}
				echo "</div>"; // card body
				echo "</div>"; // card
				echo "</div>"; // col
			}
			echo "</div>";
			?>
			
		</main>
		<?php require 'inc/footer.php'; ?>
	</body>
</html>