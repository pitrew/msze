<?php
# app/config/params.php
if (getEnv("MYSQL_USER")!='') {
	$container->setParameter('database_host', getEnv("mysql_SERVICE_HOST"));
	$container->setParameter('database_port', getEnv("mysql_SERVICE_PORT"));
	$container->setParameter('database_name', "msze");
	$container->setParameter('database_user', getEnv("MYSQL_USER"));
	$container->setParameter('database_password', getEnv("MYSQL_PASSWORD"));
}?>
