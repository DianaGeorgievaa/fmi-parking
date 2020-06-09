<?php

include '../configuration/db_config.php';
include '../utils/tableNames.php';

function getHashedPassword($connection, $email)
{
    $table = TableNames::USERS;
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
    $table = TableNames::USERS;
    $sql = "SELECT * FROM $table WHERE email = :email;";

    $resultSet = $connection->prepare($sql);
    $resultSet->bindParam(':email', $email);
    $resultSet->execute() or die("Failed to query from DB!");
    $firstrow = $resultSet->fetch(PDO::FETCH_ASSOC) or die("Invalid credentials.");
    $email = $firstrow['email'];
    $firstname = $firstrow['first_name'];
    $lastname = $firstrow['last_name'];
    $status = $firstrow['status'];

    session_start();
    $_SESSION["email"] = $email;
    $_SESSION["firstName"] = $firstname;
    $_SESSION["lastName"] = $lastname;
    $_SESSION["status"] = $status;
    header("Location:" . '../views/main.php');
} else {
    echo ("Invalid credentials.");
}
