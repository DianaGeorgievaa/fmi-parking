<?php
$configs = include('database_properties.php');

function getDatabaseConnection()
{
    global $configs;
    $host = $configs['host'];
    $dbname = $configs['database_name'];
    $username = $configs['username'];
    $password = $configs['password'];

    $connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password) or die("The connection with the database was not  established!");
    return $connection;
}
