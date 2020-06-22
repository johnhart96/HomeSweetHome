<?php
require 'inc/functions.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if( isset( $_GET['backup'] ) ) {
	// Start backup of config
	$zip = new ZipArchive;
	$filename = "data/HSHConfig_" . date( "Y-m-d-h-i" ) . ".zip";
	// Override file
	if( file_exists( $filename ) ) {
		unlink( $filename );
	}
	if ( $zip->open( $filename , ZipArchive::CREATE ) === TRUE ) {
		$zip->addFile( "data/config.db" );
		$zip->addFile( ".htpasswd" );
		// All files are added, so close the zip file.
		$zip->close();
	}
	echo "<script>window.open('" . $filename . "','_blank');</script>";
}
if( isset( $_POST['submit_restore'] ) ) {
	$target_dir = "data/";
	$target_file = $target_dir . basename( $_FILES["fileToUpload"] ["name"] );
	$uploadOk = 1;
	$imageFileType = strtolower( pathinfo( $target_file , PATHINFO_EXTENSION ) );
	// Check if file already exists
	if( file_exists( $target_file ) ) {
	  unlink( $target_file );
	}
	// Check file size
	if( $_FILES["fileToUpload"]["size"] > 500000 ) {
	  	echo "<script>alert('Sorry, your file is too large.');</script>";
	  	$uploadOk = 0;
	}
	// Allow certain file formats
	if( $imageFileType != "zip" ) {
		echo "<script>alert('Sorry, incorrect file type.');</script>";
	  	$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ( $uploadOk == 0 ) {
		echo "<script>alert('Sorry, your file was not uploaded.');</script>";
	// if everything is ok, try to upload file
	} else {
	  	if ( move_uploaded_file( $_FILES["fileToUpload"]["tmp_name"] , $target_file ) ) {
			// File uploaded
			$zip = new ZipArchive;
			if( $zip->open( $target_file ) === TRUE ) {
				$dir = getcwd();
				unlink( "data/config.db" );
				unlink( ".htpasswd" );
				$zip->extractTo( $dir );
				$zip->close();
				echo "<script>alert('Your config was restored.');</script>"; 
			} else {
				echo "<script>alert('Sorry, your config could not be restored.');</script>";
			}
	  	} else {
			echo "<script>alert('Sorry, your file was not uploaded.');</script>";
	  	}
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
			<h1>Backup / Restore Configuration</h1>
			<br />
			<div class="card">
				<div class="card-header"><strong>Backup:</strong></div>
				<div class="card-body">
					<a href="backup.php?backup" class="btn btn-success">Download Config</a>
				</div>
			</div>
			<div class="card">
				<div class="card-header"><strong>Restore:</strong></div>
				<div class="card-body">
					<form method="post" enctype="multipart/form-data">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">Choose File:</span>
							</div>
							<input type="file" name="fileToUpload" class="form-control">
							<div class="input-group-append">
								<input type="submit" name="submit_restore" value="Upload" class="btn btn-success">
							</div>
						</div>
					</form>
				</div>
			</div>
		</main>
		<?php require 'inc/footer.php'; ?>
	</body>
</html>