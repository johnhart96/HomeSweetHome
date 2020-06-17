<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'inc/functions.php';
if( isset( $_POST['submit_new'] ) ) {
	$name = $_POST['name'];
	$type = $_POST['type'];
	$parent = $_POST['parent'];
	$ip = $_POST['ip'];
	$mac = $_POST['mac'];
	$remote_type = $_POST['remote_type'];
	$ok = TRUE;
	// check ip
	$ip_oct = explode( "." , $ip );
	$oct_count = count( $ip_oct );
	if( $oct_count !== 4 ) {
		echo "<script>alert('IP address is formatted incorrectly!');</script>";
		$ok = FALSE;
	} else {
		foreach( $ip_oct as $oct ) {
			if( (int)$oct >= 255 ) {
				echo "<script>alert('IP address is formatted incorrectly!');</script>";
				$ok = FALSE;
			}
		}
	}
	// Check Mac
	$mac_oct = explode( ":" , $mac );
	$oct_count = count( $mac_oct );
	if( $oct_count !== 6 ) {
		echo "<script>alert('Mac address is formatted incorrectly!');</script>";
		$ok = FALSE;
	}
	if( $ok ) {
		$insert = $db->query( "INSERT INTO `machines`(`name`,`ip`,`mac`,`type`,`parent`,`remote_type`) VALUES('$name','$ip','$mac','$type','$parent','$remote_type')" );
		if( $insert ) {
			header( "Location: setupmachines.php?added" );
		}
	}
}
if( isset( $_POST['submit_delete'] ) ) {
	$machine = $_POST['machine'];
	$delete = $db->query( "DELETE FROM `machines` WHERE `id` ='$machine' LIMIT 1 ");
	if( $delete ) {
		header( "Location:setupmachines.php?deleted" );
	}
}
?>
<!doctype html>
<html lang="en">
	<head>
		<?php
		require 'inc/header.php';
		$getMachines = $db->query( "SELECT * FROM `machines` WHERE `type` =0" );
		?>
	</head>
	<body>
		<?php require 'inc/menu.php'; ?>
		<main role="main" class="container">
			<div class="row">
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
				
			</div>
			<h1>Setup Machines</h1>
			<?php
			if( isset( $_GET['added'] ) ) {
				echo "<div class='alert alert-success' role='alert'>";
				echo "Machine added!";
				echo "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
    			echo "<span aria-hidden='true'>&times;</span>";
  				echo "</button>";
				echo "</div>";
				echo "<br />";
			}
			if( isset( $_GET['deleted'] ) ) {
				echo "<div class='alert alert-success' role='alert'>";
				echo "Machine deleted!";
				echo "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
    			echo "<span aria-hidden='true'>&times;</span>";
  				echo "</button>";
				echo "</div>";
				echo "<br />";
			}
			
			?>
			<div class='card'>
				<div class='card-header'><strong>Current Machines:</strong></div>
				<div class="card-body">
					<ul>
						<?php
						echo "<ul>";
						while( $row = $getMachines->fetchArray() ) {
							
							echo "<li>";
							echo "<strong>";
							echo $row['name'];
							echo "</strong>";
							echo "<em> (" . $row['ip'] . ") [" . $row['mac'] . "]</em>";
							// Check for VMs
							$id = $row['id'];
							$getVMs = $db->query( "SELECT * FROM `machines` WHERE `type` =1 AND `parent` =$id" );
							$count = 0;
							while( $vm = $getVMs->fetchArray() ) {
								$count ++;
							}
							if( $count !== 0 ) {
								echo ":";
								echo "<ul>";
								// List VMs here
								while( $vm = $getVMs->fetchArray() ) {
									echo "<li>";
									echo $vm['name'];
									echo "<em> (" . $vm['ip'] . ") [" . $vm['mac'] . "]</em>";
									echo "</li>";
								}
								echo "</ul>";
							}
							echo "</li>";
							
						}
						echo "</ul>";
						?>
					</ul>
				</div>
			</div>
			<div class='card'>
				<div class='card-header'><strong>Add Machine:</strong></div>
				<div class='card-body'>
					<form method="post">
						<div class='input-group'>
							<div class='input-group-prepend'>
								<span class='input-group-text'>Name:</span>
							</div>
							<input type='text' name='name' placeholder='Family Desktop' class='form-control'>
						</div>
						<div class='input-group'>
							<div class='input-group-prepend'>
								<span class='input-group-text'>Type:</span>
							</div>
							<select name='type' class='form-control'>
								<option value='0' selected>Physical Machine</option>
								<option value='1'>Virtual Machine</option>
							</select>
						</div>
						<div class='input-group'>
							<div class='input-group-prepend'>
								<span class='input-group-text'>Parent:</span>
							</div>
							<select name='parent' class='form-control'>
								<option value='0' selected>None</option>
								<?php
								while( $row = $getMachines->fetchArray() ) {
									echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
								}
								?>
							</select>
						</div>
						<div class='input-group'>
							<div class='input-group-prepend'>
								<span class='input-group-text'>IP:</span>
							</div>
							<input type='text' name='ip' placeholder='192.168.1.4' class='form-control'>
						</div>
						<div class='input-group'>
							<div class='input-group-prepend'>
								<span class='input-group-text'>Mac Address:</span>
							</div>
							<input type='text' name='mac' placeholder='FF:FF:FF:FF:FF:FF' class='form-control'>
						</div>
						<div class='input-group'>
							<div class='input-group-prepend'>
								<span class='input-group-text'>Remote Type:</span>
							</div>
							<select name="remote_type" class="form-control">
								<option value='0'>Remote Desktop Connection (RDP)</option>
								<option value='1'>VNC</option>
								<option value='2'>SSH</option>
							</select>
						</div>
						

						<br />
						<button class='btn btn-success' name='submit_new' type='submit'>Save</button>
					</form>
				</div>
			</div>
			<div class='card'>
				<div class='card-header'><strong>Delete Machine:</strong>
				</div>
				<div class='card-body'>
					<form method="post">
						<div class='input-group'>
							<div class='input-group-prepend'>
								<span class='input-group-text'>Select:</span>
							</div>
							<select name='machine' class='form-control'>
								<option selected disabled>--Select--</option>
								<?php
								$getAll = $db->query( "SELECT * FROM `machines`" );
								while ( $row = $getAll->fetchArray() ) {
									echo "<option value='" . $row[ 'id' ] . "'>" . $row[ 'name' ] . "</option>";
								}
								?>
							</select>
							<div class='input-group-append' id='button-addon4'>
								<button class='btn btn-danger' type='submit' name='submit_delete'>Delete</button>
							</div>
						</div>

					</form>
				</div>
			</div>
		</main>
		<?php require 'inc/footer.php'; ?>
	</body>
</html>