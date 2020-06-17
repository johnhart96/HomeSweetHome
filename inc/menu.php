<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
	<a class="navbar-brand" href="index.php">HomeSweetHome</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>



	<div class="collapse navbar-collapse" id="navbarsExampleDefault">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item active">
				<a class="nav-link" href="index.php">Dashboard <span class="sr-only">(current)</span></a>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Machines</a>
				<div class="dropdown-menu" aria-labelledby="dropdown01">
					<?php
					$getMachines = $db->query( "SELECT `id`,`name` FROM `machines`" );
					while( $row = $getMachines->fetchArray() ) {
						echo "<a class='dropdown-item' href='" . "index.php?parent=" . $row['id'] . "'>" . $row['name'] . "</a>";
					} 
					?>
				</div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Settings</a>
				<div class="dropdown-menu" aria-labelledby="dropdown01">
					<a class="dropdown-item" href="users.php">Users</a>
					<a class="dropdown-item" href="setupmachines.php">Machines</a>
				</div>
			</li>
		</ul>
		<form class="form-inline my-2 my-lg-0" action="quickwake.php" method="post">
			<input name="macAddress" class="form-control mr-sm-2" type="text" placeholder="Mac Address" aria-label="Mac Address">
			<button class="btn btn-secondary my-2 my-sm-0" type="submit">Wake</button>
		</form>
	</div>
</nav>