<?php
include '../utils/tableNames.php';
include '../utils/databaseQueriesUtils.php';

$email = $_POST["email"];
$password = $_POST["password"];
$hashedPassword = DatabaseQueriesUtils::getHashedPassword($email);

if ($hashedPassword == "") {
    die("Invalid credentials.");
}

if (password_verify($password, $hashedPassword)) {
    $user = DatabaseQueriesUtils::getUserByEmail($email);
    
    $email = $user['email'];
    $firstname = $user['first_name'];
    $lastname = $user['last_name'];
    $status = $user['status'];

    session_start();
    $_SESSION["email"] = $email;
    $_SESSION["firstName"] = $firstname;
    $_SESSION["lastName"] = $lastname;
    $_SESSION["status"] = $status;
    header("Location:" . '../views/main.php');
} else {
    echo ("Invalid credentials.");
}
