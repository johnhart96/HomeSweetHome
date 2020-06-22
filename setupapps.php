<?php
require 'inc/functions.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if( isset( $_POST['submit_new'] ) ) {
	$name = secureInput( $_POST['name'] );
	$URL = secureInput( $_POST['url'] );
	$machine = secureInput( $_POST['machine'] );
	
	$insert = $db->query( "INSERT INTO `applications`(`name`,`url`,`machine`) VALUES('$name','$URL','$machine')" );
	if( ! $insert ) {
		die( "Failed" );
	} else {
		go( "setupapps.php?added" );
	}
}
if( isset( $_POST['submit_delete'] ) ) {
	$delete = secureInput( $_POST['delete'] );
	$del = $db->query( "DELETE FROM `applications` WHERE `id` ='$delete' LIMIT 1" );
	if( ! $del ) {
		die( "Failed" );
	} else {
		go( "setupapps.php?deleted" );
	}
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
			<h1>Setup Applications</h1>
			<br />
			<?php
			if( isset( $_GET['deleted'] ) ) {
				echo '<div class="alert alert-success" role="alert">Application deleted!</div>';
			}
			if( isset( $_GET['added'] ) ) {
				echo '<div class="alert alert-success" role="alert">Application added!</div>';
			}
			$getApps = $db->query( "SELECT * FROM `applications`" );
			?>
			<div class="card">
				<div class="card-header"><strong>Current Applications:</strong></div>
				<div class="card-body">
					<ul>
						<?php
						while( $row = $getApps->fetchArray() ) {
							$machine = (int)$row['machine'];
							$getMachine = $db->query( "SELECT `name` FROM `machines` WHERE `id` ='$machine' LIMIT 1" );
							while( $mac = $getMachine->fetchArray() ) {
								echo "<li>" . $row['name'] . " on " . $mac['name'] . "</li>";
							}	
						}
						?>
					</ul>
				</div>
			</div>
			<div class="card">
				<div class="card-header"><strong>New Application</strong></div>
				<div class="card-body">
					<form method="post">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">Name:</span>
							</div>
							<input type="text" name="name" placeholder="Freenas" class="form-control">
						</div>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">URL:</span>
							</div>
							<input type="text" name="url" placeholder="http://192.168.1.1/" class="form-control">
						</div>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">Machine:</span>
							</div>
							<select name="machine" class="form-control">
								<option select disabled>--Select--</option>
								<?php
								$getMachines = $db->query( "SELECT * FROM `machines`" );
								while( $row = $getMachines->fetchArray() ) {
									echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
								}
								?>
							</select>
						</div>
						<br />
						<input type="submit" value="Save" name="submit_new" class="btn btn-success">
					</form>
				</div>
			</div>
			<div class="card">
				<div class="card-header"><strong>Delete Application</strong></div>
				<div class="card-body">
					<form method="post">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">Select:</span>
							</div>
							<select name="delete" class="form-control">
								<option selected disabled>--Select--</option>
								<?php
								while( $app = $getApps->fetchArray() ) {
									echo "<option value='" . $app['id'] . "'>" . $app['name'] . "</option>";
								}
								?>
							</select>
							<div class="input-group-append">
								<button type="submit" name="submit_delete" class="btn btn-danger">Delete</button>
								
							</div>
						</div>
					</form>
				</div>
			</div>
		</main>
		<?php require 'inc/footer.php'; ?>
	</body>
</html>