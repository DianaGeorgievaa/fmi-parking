<?php

include '../configuration/db_config.php';

function getHashedPassword($connection, $email)
{
    $table = "users";
    $sqlQueryForPassword = "SELECT password FROM $table WHERE email = :email;";
    $getHashedPassword = $connection->prepare($sqlQueryForPassword);
    $getHashedPassword->bindParam(':email', $email);
    $getHashedPassword->execute() or die("Invalid credentials.");

    $result = $getHashedPassword->fetch(PDO::FETCH_BOTH);
    $hashedPassword = $result['password'];

    return $hashedPassword;
}

$connection = getDatabaseConnection();

$email = $_POST["email"];
$password = $_POST["password"];
$hashedPassword = getHashedPassword($connection, $email);

if (!isset($hashedPassword)) {
    die("Invalid credentials.");
}

if (password_verify($password, $hashedPassword)) {
    $table = "users";
    $sql = "SELECT * from $table WHERE email = :email;";

    $resultSet = $connection->prepare($sql);
    $resultSet->bindParam(':email', $email);
    $resultSet->execute() or die("Failed to query from DB!");
    $firstrow = $resultSet->fetch(PDO::FETCH_ASSOC) or die("Invalid credentials.");

    echo ("Hello " . $firstrow['first_name'] . " you are now logged in.");
    session_start();
    $_SESSION["email"] = $firstrow['email'];
} else {
    echo ("Invalid credentials.");
}
