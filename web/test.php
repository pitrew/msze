<?php
echo "Hello";
echo getEnv("JAWSDB_URL");

if (getEnv("JAWSDB_URL")!='') {
	$url = getenv('JAWSDB_URL');
	$dbparts = parse_url($url);

	$hostname = $dbparts['host'];
	$username = $dbparts['user'];
	$password = $dbparts['pass'];
	$port = $dbparts['port'];
	$database = ltrim($dbparts['path'],'/');

    echo "<br/>";
    echo $hostname;
    echo "<br/>";
    echo $port;
    echo "<br/>";
	
    echo $database;
    echo "<br/>";
    echo $username;
    echo "<br/>";
    // echo $password;
    echo "<br/>";
} else {
    echo 'NIEEE';
}