<?php
# app/config/params.php
if (getEnv("MYSQL_SERVICE_HOST")!='') {
	$container->setParameter('database_host', getEnv("MYSQL_SERVICE_HOST"));
	$container->setParameter('database_port', getEnv("MYSQL_SERVICE_PORT"));
	// $container->setParameter('database_name', getEnv("MYSQL_DATABASE"));
	// $container->setParameter('database_user', getEnv("MYSQL_USER"));
	// $container->setParameter('database_password', getEnv("MYSQL_PASSWORD"));

	$container->setParameter('database_name', "msze");
	$container->setParameter('database_user', "adminquM8Lrf");
	$container->setParameter('database_password', "c3a9Xnuycipb");
}

if (getEnv("JAWSDB_URL")!='') {
	$url = getenv('JAWSDB_URL');
	$dbparts = parse_url($url);

	$hostname = $dbparts['host'];
	$username = $dbparts['user'];
	$password = $dbparts['pass'];
	$port = $dbparts['port'];
	$database = ltrim($dbparts['path'],'/');

	$container->setParameter('database_host', $hostname);
	$container->setParameter('database_port', $port);
	// $container->setParameter('database_name', getEnv("MYSQL_DATABASE"));
	// $container->setParameter('database_user', getEnv("MYSQL_USER"));
	// $container->setParameter('database_password', getEnv("MYSQL_PASSWORD"));

	$container->setParameter('database_name', $database);
	$container->setParameter('database_user', $username);
	$container->setParameter('database_password', $password);
}
?>



