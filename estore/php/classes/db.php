<?php
define('HOSTNAME', 'localhost:3306');
define('DB_HOST', HOSTNAME);
define('DB_USERNAME', 'root'); // CHANGE HERE
define('DB_PASSWORD', ''); // CHANGE HERE
define('DB_NAME', 'estore');

$db_settings = array(
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);

function getConn()
{
    global $db_settings;
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD, $db_settings);
    return $conn;
}

?>
