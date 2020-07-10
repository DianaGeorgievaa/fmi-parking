<?php
$configs = include __DIR__ . ('/../configuration/database_properties.php');

function getDatabaseConnection()
{
    global $configs;
    $host = $configs['host'];
    $dbname = $configs['database_name'];
    $username = $configs['username'];
    $password = $configs['password'];

    $connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password) or Utils::showMessage(MessageUtils::NOT_ESTABLISHED_DATABASE_MESSAGE, false);
    return $connection;
}
