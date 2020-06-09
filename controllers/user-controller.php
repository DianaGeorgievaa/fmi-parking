<?php
include '../configuration/db_config.php';


function getUserPoints()
{
    $email = isset($_SESSION["email"]);

    $table = TableNames::USERS;
    $sql = "SELECT points from $table WHERE email = :email;";
    $connection = getDatabaseConnection();
    $resultSet = $connection->prepare($sql);
    $resultSet->bindParam(':email', $email);
    $resultSet->execute() or die("Failed to query from DB!");
    $firstrow = $resultSet->fetch(PDO::FETCH_ASSOC) or die("Invalid credentials.");
    $points = $firstrow['points'];

    return $points;
}


function getAllUsers()
{
    $email = isset($_SESSION["email"]);

    $table = TableNames::USERS;
    $sql = "SELECT first_name, last_name from $table;";
    $connection = getDatabaseConnection();
    $resultSet = $connection->prepare($sql);
    $resultSet->execute() or  die("Failed to query from DB!");

    echo ("The users in the system are: <br>");
    while ($row = $resultSet->fetch(PDO::FETCH_ASSOC)) {
        // TODO print user names and add href to profile page with button from home page
        // echo $row['email'] . " " . $row['firstname'] . " " . $row['role'];
        // echo "<br>";
    }
}
