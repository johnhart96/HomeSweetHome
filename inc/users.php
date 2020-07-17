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
				<h1>Users</h1>
				<table width="100%" border="1">
					<thead>
						<tr>
							<th>Username</th>
							<th>Password</th>
						</tr>
					</thead>
				</table>
			</div>
		</main>
		<?php require 'inc/footer.php'; ?>
	</body>
</html>