<?php
include '../utils/databaseQueriesUtils.php';

$email = $_POST["email"];
$password = $_POST["password"];
$hashedPassword = DatabaseQueriesUtils::getHashedPassword($email);

if ($hashedPassword == "") {
    Utils::showMessage(MessageUtils::INVALID_CREDENTIALS_MESSAGE, false);
}

if (password_verify($password, $hashedPassword)) {
    $user = DatabaseQueriesUtils::getUserByEmail($email);

    $email = $user['email'];
    $firstname = $user['first_name'];
    $lastname = $user['last_name'];
    $status = $user['status'];
    $carNumber = $user['car_number'];

    session_start();
    $_SESSION["email"] = $email;
    $_SESSION["firstName"] = $firstname;
    $_SESSION["lastName"] = $lastname;
    $_SESSION["status"] = $status;
    $_SESSION["carNumber"] = $carNumber;
    header("Location:" . '../views/main.php');
} else {
    Utils::showMessage(MessageUtils::INVALID_CREDENTIALS_MESSAGE, false);
}
