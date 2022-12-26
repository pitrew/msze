<?php
if (getEnv("MYSQL_SERVICE_HOST")!='') {
        $container->setParameter('database_host', getEnv("MYSQL_SERVICE_HOST"));
        $container->setParameter('database_port', getEnv("MYSQL_SERVICE_PORT"));
        $container->setParameter('database_name', getEnv("MYSQL_DATABASE"));
        $container->setParameter('database_user', getEnv("MYSQL_USER"));
        $container->setParameter('database_password', getEnv("MYSQL_PASSWORD"));
}
?>



