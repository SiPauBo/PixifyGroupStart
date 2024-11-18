<?php

$serverName = "itp-srv.mysql.database.azure.com";
$dbusername = "pixify_admin";
$password = "@5mGxli#58Sy";
$database = "pixify_db";
$sslCertPath = __DIR__ . "/../certs/BaltimoreCyberTrustRoot.crt.pem"; // Adjust the path if necessary


// Initialize MySQLi connection
$connection = mysqli_init();
if (!$connection) {
    die("mysqli_init failed");
}

// Configure SSL for MySQLi
mysqli_ssl_set($connection, NULL, NULL, $sslCertPath, NULL, NULL);

// Establish a secure connection
if (!$connection->real_connect($serverName, $dbusername, $password, $database, 3306, NULL, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT)) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


