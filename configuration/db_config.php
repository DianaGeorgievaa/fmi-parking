<?php

define("DATABASE_HOST", "localhost", true);
define("USERNAME", "root", true);
define("PASSWORD", "", true);
define("DATABASE_NAME", "fmi_parking", true);

function getDatabaseConnection()
{
    $connection = new PDO("mysql:host=DATABASE_HOST;dbname=DATABASE_NAME", USERNAME, PASSWORD) or die("The connection with the database was not established!");
    return $connection;
}
