<?php

if( ! file_exists( "data/config.db" ) ) {
	die( "No configuration file could be found!" );
} else {
	$db = new SQLite3( "data/config.db" );
}

function ping( $host ) {
    exec( sprintf( 'ping -c 1 -W 5 %s' , escapeshellarg( $host ) ) , $res , $rval );
    return $rval === 0;
}

function rdp( $id , $ip ) {
	$default = fopen( "data/default_rdp.txt" , "r" );
	$startingPoint = fread( $default , filesize( "data/default_rdp.txt" ) );
	$toSave = str_replace( "{IP}" , $ip , $startingPoint );
	$thisMachine = fopen( "data/" . $id . ".rdp" , "w" );
	fwrite( $thisMachine , $toSave );
	fclose( $thisMachine );
	fclose( $default );
}
function vnc( $id , $ip ) {
	$default = fopen( "data/default_vnc.txt" , "r" );
	$startingPoint = fread( $default , filesize( "data/default_vnc.txt" ) );
	$toSave = str_replace( "{IP}" , $ip , $startingPoint );
	$toSave = str_replace( "{NAME}" , $id , $toSave );
	$thisMachine = fopen( "data/" . $id . ".vnc" , "w" );
	fwrite( $thisMachine , $toSave );
	fclose( $thisMachine );
	fclose( $default );
}
function go( $location ) {
  echo "<script>window.location='" . $location . "'</script>";
  return NULL;
}
function secureInput( $input ) {
  return filter_var( $input , FILTER_SANITIZE_STRING );
}

function wol( $macaddr ) {
	// UDP/IP settings
	$dest = '255.255.255.255'; // Broadcast domain only
	$port = '9'; // Discard port

	// Validate and format macaddress into a binary string array
	if (!filter_var($macaddr, FILTER_VALIDATE_MAC)) { return FALSE; }
	$macaddr = preg_replace('/[^0-9a-fA-F]/', ':', $macaddr);
	$macaddr = explode(":", $macaddr);
	for ($n = 0; $n < count($macaddr); $n++) {
		$macaddr[$n] = hexdec($macaddr[$n]);
	}
	if (count($macaddr) != 6) { return FALSE; };

	// Construct WOL magic packet
	$len = '102'; // WOL Magic packet length
	$magicpacket = pack("C*", 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF); // Synchronization Stream
	for ($n = 0; $n < 16; $n++) { // Target MAC (x16)
		$magicpacket .= pack("C*", ...$macaddr);
	}
	if (strlen($magicpacket) != $len) { return FALSE; };

	// Create broadcast UDP socket
	$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
	$ok = socket_set_option($sock, SOL_SOCKET, SO_BROADCAST, 1);
	if (!$ok) { return FALSE; }

	// Send thrice
	for ($n = 0; $n < 3; $n++) {
  	$ok = socket_sendto($sock, $magicpacket, $len, 0, $dest, $port);
		if (!$ok) { return FALSE; }
	}

	// Clean up
	socket_close($sock);

	return TRUE;
}
?>
