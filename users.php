<?php
require 'inc/functions.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$users = file( ".htpasswd" );

if( isset( $_POST['submit_new'] ) ) {
	$username = $_POST['username'];
	$password = $_POST['password'];
	// Check for existing user
	$ok = TRUE;
	foreach( $users as $user ) {
		$part = explode( ":" , $user );
		if( $part[0] == $username ) {
			$ok = FALSE;
		}
	}
	if( $ok ) {
		$command = "htpasswd -mb /var/www/html/.htpasswd " . $username . " " . $password;
		shell_exec( $command );
		echo "<script>alert('User added');</script>";
	}
}
if( isset( $_POST['submit_delete'] ) ) {
	$username = $_POST['username'];
	
	$command = "htpasswd -D /var/www/html/.htpasswd " . $username;
	shell_exec( $command );
	echo "<script>alert('User deleted!');</script>";
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
			<h1>Setup Users</h1>
			<div class='card'>
				<div class='card-header'><strong>Current Users:</strong></div>
				<div class='card-body'>
					<ul style="display: grid;">
						<?php
						$count = 0;
						foreach( $users as $user ) {
							echo "<li>";
							$part = explode( ":" , $user );
							echo $part[0];
							echo "</li>";
							$count ++;
						}
						?>
					</ul>	
				</div>
			</div>
			<div class='card'>
				<div class='card-header'><strong>Add User:</strong></div>
				<div class='card-body'>
					<form method="post">
						<div class='input-group'>
							<div class='input-group-prepend'>
								<span class='input-group-text'>Username:</span>
							</div>
							<input class='form-control' name='username' placeholder='Bob'>
						</div>
						<div class='input-group'>
							<div class='input-group-prepend'>
								<span class='input-group-text'>Password:</span>
							</div>
							<input class='form-control' name='password' placeholder='*******'>
						</div>
						<br />
						<div class='input-group'>
							<button class='btn btn-success' type='submit' name='submit_new'>Save</button>
						</div>
					</form>
				</div>
			</div>
			<div class='card'>
				<div class='card-header'><strong>Delete User:</strong></div>
				<div class='card-body'>
					<form method="post">
						<div class='input-group'>
							<div class='input-group-prepend'>
								<span class='input-group-text'>Select:</span>
							</div>
							<select class='form-control' name='username' aria-describedby='button-addon4'>
								<option selected disabled>--Select--</option>
								<?php
								foreach( $users as $user ) {
									echo "<li>";
									$part = explode( ":" , $user );
									echo $part[0];
									if( $part[0] == current_user ) {
										// cannot delete
										echo "<option disabled value='" . $part[0] . "'>" . $part[0] . " (cannot delete current user)</option>";
									} else {
										// can delete
										echo "<option value='" . $part[0] . "'>" . $part[0] . "</option>";
									}
									echo "</li>";
									$count ++;
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