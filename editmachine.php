<?php require 'inc/functions.php'; ?>
<?php
if( isset( $_POST['submit_edit'] ) ) {
	$name = secureInput( $_POST['name'] );
	$type = (int)$_POST['type'];
	$parent = (int)$_POST['parent'];
	$ip = secureInput( $_POST['ip'] );
	$mac = secureInput( $_POST['mac'] );
	$remote_type = (int)$_POST['remote_type'];
	$id = (int)$_GET['id'];
	
	$update = $db->query("
		UPDATE `machines` SET
		`name` ='$name',
		`type` ='$type',
		`parent` ='$parent',
		`ip` ='$ip',
		`mac` ='$mac',
		`remote_type` ='$remote_type'
		WHERE `id` ='$id' LIMIT 1
	");
	if( ! $update ) {
		die( $db->error );
	} else {
		go( "setupmachines.php" );
	}
}
?>
<!doctype html>
<html lang="en">
	<head>
		<?php
		require 'inc/header.php';
		if( empty( $_GET['id'] ) ) {
			die( "No ID was passed!" );
		} else {
			$id = secureInput( (int)$_GET['id'] );
		}
		$getMachine = $db->query( "SELECT * FROM `machines` WHERE `id` ='$id' LIMIT 1" );
		$current = $getMachine->fetchArray();
		?>
	</head>
	<body>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<?php require 'inc/menu.php'; ?>
		<main role="main" class="container">
			<h1>Edit Machine</h1>
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
				<li class="breadcrumb-item"><a href="setupmachines.php">Setup Machines</a></li>
				<li class="breadcrumb-item active" aria-current="page"><?php echo $current['name']; ?></li>
			  </ol>
			</nav>
			<div class="card">
				<div class="card-header"><strong><?php echo $current['name']; ?></strong></div>
				<div class="card-body">
					<form method="post">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">Name:</span>
							</div>
							<input type="text" name="name" value="<?php echo $current['name']; ?>" class="form-control">
						</div>
						
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">Type:</span>
							</div>
							<select name='type' class='form-control'>
								<?php
								if( (int)$current == 1 ) {
									echo "<option value='0'>Physical Machine</option><option selected value='1'>Virtual Machine</option>";
								} else {
									echo "<option selected value='0'>Physical Machine</option><option value='1'>Virtual Machine</option>";
								}
								?>
								
							</select>
						</div>
						
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">Parent:</span>
							</div>
							<select name="parent" class="form-control">
								<option value='0'>None</option>
								<?php
								$cParent = (int)$current['parent'];
								$getParents = $db->query( "SELECT * FROM `machines` WHERE `type` =0" );
								while( $parent = $getParents->fetchArray() ) {
									if( $cParent == (int)$parent['id'] ) {
										echo "<option selected value='" . $parent['id'] . "'>" . $parent['name'] . "</option>";
									} else {
										echo "<option value='" . $parent['id'] . "'>" . $parent['name'] . "</option>";
									}
								}
								?>
							</select>
						</div>
						
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">IP:</span>
							</div>
							<input type="text" name="ip" value="<?php echo $current['ip']; ?>" class="form-control">
						</div>
						
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">Mac Address:</span>
							</div>
							<input type="text" name="mac" value="<?php echo $current['mac']; ?>" class="form-control">
						</div>
						
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">Remote Type:</span>
							</div>
							<select name="remote_type" class="form-control">
								<?php
								$cType = (int)$current['remote_type'];
								if( $cType == 0 ) {
									echo "
										<option selected value='0'>Remote Desktop Connection (RDP)</option>
										<option value='1'>VNC</option>
										<option value='2'>SSH</option>
										<option value='3'>HTTP</option>
										<option value='4'>HTTPS</option>
									";
								} else if( $cType == 1 ) {
									echo "
										<option value='0'>Remote Desktop Connection (RDP)</option>
										<option selected value='1'>VNC</option>
										<option value='2'>SSH</option>
										<option value='3'>HTTP</option>
										<option value='4'>HTTPS</option>
									";
								} else if( $cType == 2 ) {
									echo "
										<option value='0'>Remote Desktop Connection (RDP)</option>
										<option value='1'>VNC</option>
										<option selected value='2'>SSH</option>
										<option value='3'>HTTP</option>
										<option value='4'>HTTPS</option>
									";
								} else if( $cType == 3 ) {
									echo "
										<option value='0'>Remote Desktop Connection (RDP)</option>
										<option value='1'>VNC</option>
										<option value='2'>SSH</option>
										<option selected value='3'>HTTP</option>
										<option value='4'>HTTPS</option>
									";
								} else if( $cType == 4 ) {
									echo "
										<option value='0'>Remote Desktop Connection (RDP)</option>
										<option value='1'>VNC</option>
										<option value='2'>SSH</option>
										<option value='3'>HTTP</option>
										<option selected value='4'>HTTPS</option>
									";
								}
								?>
							</select>
						</div>
						<br />
						<button name="submit_edit" class="btn btn-success" type="submit">Save</button>
					</form>
				</div>
			</div>
		</main>
		<?php require 'inc/footer.php'; ?>
	</body>
</html>
