<?php
require 'inc/functions.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$file = "/etc/dnsmasq.d/hsh.dns";
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
			<h1>DHCP &amp; DNS</h1>
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
				<li class="breadcrumb-item active" aria-current="page">DHCP &amp; DNS</li>
			  </ol>
			</nav>
			<?php
			if( isset( $_POST['submit_edit'] ) ) {
				$dns_domain = filter_var( $_POST['dns_domain'] , FILTER_SANITIZE_STRING );
				$range_start = filter_var( $_POST['range_start'] , FILTER_VALIDATE_IP );
				$range_end = filter_var( $_POST['range_end'] , FILTER_VALIDATE_IP );
				$leasetime = filter_var( $_POST['leasetime'] , FILTER_SANITIZE_NUMBER_INT ) . "h";
				$router = filter_var( $_POST['router'] , FILTER_VALIDATE_IP );
				$dns = filter_var( $_POST['dns'] , FILTER_VALIDATE_IP );
				$dns_cache = filter_var( $_POST['dns_cache'] , FILTER_SANITIZE_NUMBER_INT );

				$editfile = fopen( $file , "w" );
				$range = $range_start . "," . $range_end . "," . $leasetime;
				$construct =
"
# DHCP
domain-needed
bogus-priv
domain=$dns_domain
dhcp-range=$range
dhcp-option=3,$router
dhcp-option=6,$dns

# DNS
cache-size=$dns_cache
";
				fwrite( $editfile , $construct );
				fclose( $editfile );
				echo "<div class='alert alert-warning' role='alert'>Changes saved. You may need to restart your server to reload the changes.</div>";
			}
			?>
			
			<div class="card">
				<div class="card-header"><strong>Service Status:</strong></div>
				<div class="card-body">
					<?php
					exec( "pgrep dnsmasq", $output, $return );
					if( $return == 0 ) {
						echo "<div class='alert alert-success' role='alert'>DNS &amp; DNS Services are running!</div>";
					} else {
						echo "<div class='alert alert-danger' role='alert'>DNS &amp; DNS Services are not running!<br />You may need to enable it using commend: <br /><br />
						<span style='margin-top: 10px; padding: 5px; border: solid 1px #000;'><em>sudo systemctl enable dnsmasq & sudo systemctl start dnsmasq</em></span>
						</div>";
					}
					$server_ip = $_SERVER['SERVER_ADDR'];
					
					?>
				</div>
			</div>
			
			<div class="card">
				<div class="card-header"><strong>Current:</strong></div>
				<div class="card-body">
					<table width="100%" border="1">
						<thead>
							<tr>
								<th>Setting</th>
								<th>Value</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$lines = file( $file );
							foreach( $lines as $line ) {
								
								$bit = explode( "=" , $line );
								if( count( $bit ) == 2 ) {
									// Settings
									switch( $bit[0] ) {
										case "domain":
											echo "<tr>";
											echo "<td>DNS Domain:</td>";
											echo "<td>" . $bit[1] . "</td>";
											$dns_domain = filter_var( $bit[1] , FILTER_SANITIZE_STRING );
											echo "</tr>";
											break;
										case "dhcp-range":
											$range_bit = explode( "," , $bit[1] );
											echo "<tr><td>DHCP Range Start</td>";
											echo "<td>" . $range_bit[0] . "</td></tr>";
											$range_start = filter_var( $range_bit[0] , FILTER_VALIDATE_IP );
											echo "<tr><td>DHCP Range End</td>";
											echo "<td>" . $range_bit[1] . "</td></tr>";
											$range_end = filter_var( $range_bit[1] , FILTER_VALIDATE_IP );
											echo "<tr><td>DHCP Lease:</td>";
											echo "<td>" . $range_bit[2] . "</td></tr>";
											$leasetime = str_replace( "h" , "" , filter_var( $range_bit[2] , FILTER_SANITIZE_STRING ) );
											break;
										case "dhcp-option":
											$option_bit = explode( "," , $bit[1] );
											if( (int)$option_bit[0] == 3 ) {
												echo "<tr><td>DHCP Router:</td>";
												echo "<td>" . $option_bit[1] . "</td></tr>";
												$router = filter_var( $option_bit[1] , FILTER_SANITIZE_STRING );
												//$router = $option_bit[1];
											} else if( (int)$option_bit[0] == 6 ) {
												echo "<tr><td>DHCP DNS Server:</td>";
												echo "<td>" . $option_bit[1] . "</td></tr>";
												$dns = filter_var( $option_bit[1] , FILTER_SANITIZE_STRING );
											}
											break;
										case "cache-size":
											echo "<tr><td>DNS Cache Size:</td>";
											echo "<td>" . $bit[1] . "MB</td></tr>";
											$dns_cache = filter_var( $bit[1] , FILTER_SANITIZE_NUMBER_INT );
									}
									
									echo "</tr>";
								}
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
			
			<div class="card">
				<div class="card-header"><strong>Edit:</strong></div>
				<div class="card-body">
					<form method="post">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">DNS Domain:</span>
							</div>
							<input class="form-control" name="dns_domain" value="<?php echo $dns_domain; ?>">
						</div>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">DNS Cache Size:</span>
							</div>
							<input class="form-control" name="dns_cache" value="<?php echo $dns_cache; ?>">
							<div class="input-group-append">
								<span class="input-group-text">MB</span>
							</div>
						</div>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">DHCP Range Start:</span>
							</div>
							<input placeholder="192.168.1.2" class="form-control" name="range_start" value="<?php echo $range_start; ?>">
						</div>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">DHCP Range End:</span>
							</div>
							<input placeholder="192.168.1.254" class="form-control" name="range_end" value="<?php echo $range_end; ?>">
						</div>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">DHCP Lease Time:</span>
							</div>
							<input placeholder="192.168.1.254" class="form-control" name="leasetime" value="<?php echo $leasetime; ?>">
							<div class="input-group-append">
								<span class="input-group-text">Hours</span>
							</div>
						</div>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">DHCP Router IP:</span>
							</div>
							<input placeholder="192.168.1.1" class="form-control" name="router" value="<?php echo $router; ?>">
						</div>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">DHCP DNS Server:</span>
							</div>
							<input placeholder="192.168.1.1" class="form-control" name="dns" value="<?php echo $dns; ?>">
						</div>
						<br />
						<button class="btn btn-success" type="submit" name="submit_edit">Save</button>
					</form>
				</div>
			</div>
		</main>
		<?php require 'inc/footer.php'; ?>
	</body>
</html>