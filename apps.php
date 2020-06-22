<?php
require 'inc/functions.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
			<h1>Applications</h1>
			<?php
			$getApps = $db->query( "SELECT * FROM `applications` ORDER BY `name` ASC" );
			?>
			<div class="row">
				<?php
				while( $app = $getApps->fetchArray() ) {
					echo "<div class='col-sm-6'>";
					echo "<div class='card'>";
					echo "<div class='card-body'>";
					echo "<h5 class='card-title'>" . $app['name'] . "</h5>";
					echo "<p class='card-text'>";
					$machine = (int)$app['machine'];
					$getMachine = $db->query( "SELECT `name`,`ip` FROM `machines` WHERE `id` ='$machine' LIMIT 1" );
					while( $mac = $getMachine->fetchArray() ) {
						$machineName = $mac['name'];
						$ip = $mac['ip'];
					}	
					echo "<em>on " . $machineName . " (<a href='index.php?parent=" . $machine . "'>Check Status</a>)</em> <br />";
					echo "</p>";
					echo "<a href='" . $app['URL'] . "' target='_blank' class='btn btn-primary'>Open Application</a>";
					echo "</div>"; // Card body
					echo "</div>"; // Card
					echo "</div>"; // Col
				}
				?>
			</div>
		</main>
		<?php require 'inc/footer.php'; ?>
	</body>
</html>