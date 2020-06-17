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
?>