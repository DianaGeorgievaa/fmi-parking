<?php

include '../configuration/db_config.php';
include '../utils/tableNames.php';
include '../utils/utils.php';
include '../views/menu.php';

function getUser($userId)
{

    $table = TableNames::USERS;
    $sql = "SELECT * FROM $table WHERE user_id = :userId;";

    $connection = getDatabaseConnection();
    $resultSet = $connection->prepare($sql);
    $resultSet->bindParam(':userId', $userId);
    $resultSet->execute() or die("Failed to query from DB!");
    $user = $resultSet->fetch(PDO::FETCH_ASSOC) or die("Failed to get user.");

    return $user;
}

function getUserParkingInfoId($userId)
{
    $table = TableNames::USER_PARKING_INFO;
    $sql = "SELECT parking_spot_id FROM $table WHERE user_id = :userId;";

    $connection = getDatabaseConnection();
    $resultSet = $connection->prepare($sql);
    $resultSet->bindParam(':userId', $userId);
    $resultSet->execute() or die("Failed to query from DB!");
    $parkingSpotInfo = $resultSet->fetch(PDO::FETCH_ASSOC) or die("Failed to get user.");

    return $parkingSpotInfo;
}

function getParkingSpot($parkingSpotId)
{
    $table = TableNames::PARKING_SPOT;
    $sql = "SELECT * FROM $table WHERE parking_spot_id = :parkingSpotId;";

    $connection = getDatabaseConnection();
    $resultSet = $connection->prepare($sql);
    $resultSet->bindParam(':parkingSpotId', $parkingSpotId);
    $resultSet->execute() or die("Failed to query from DB!");
    $parkingSpot = $resultSet->fetch(PDO::FETCH_ASSOC) or die("Failed to get user.");

    return $parkingSpot;
}

function saveViewer($userId)
{
    $table = TableNames::PROFILE_VIEWER;
    $sql = "INSERT INTO $table (first_name, last_name, email, view_time, user_id) 
                            VALUES (:firstname, :lastname, :email, :viewTime, :userId);";

    $viewerFirstname = $_SESSION['firstName'];
    $viewerLastname = $_SESSION['lastName'];
    $viewerEmail =  $_SESSION["email"];
    $viewTime = date("h:i:s");

    $connection = getDatabaseConnection();
    $resultSet = $connection->prepare($sql);
    $resultSet->bindParam(':firstname', $viewerFirstname);
    $resultSet->bindParam(':lastname', $viewerLastname);
    $resultSet->bindParam(':email', $viewerEmail);
    $resultSet->bindParam(':viewTime', $viewTime);
    $resultSet->bindParam(':userId', $userId);
    $resultSet->execute() or die("Failed to query from DB!");
}

if (isLoggedInUser()) {
    $userId = $_SERVER['userId'];;
    $user = getUser($userId);
    $firstname = $user['first_name'];
    $lastname = $user['last_name'];
    $email = $user['email'];
    $userPoints = $user['points'];
    $userStatus = $user['status'];
    $photoName = $user['photo_name'];
    $photoPath = Utils::USER_PHOTO_FOLDER_PATH . $photoName;

    saveViewer($userId);

    $parkingSpotId = getUserParkingInfoId($userId);
    $parkingSpot = getParkingSpot($parkingSpotId);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <title>User profile</title>
</head>

<body>
    <img src="<?php echo $photoPath ?>">
    <label><?php echo "Firstname: $firstname" ?></label>
    <label><?php echo "Lastname: $lastname" ?></label>
    <label><?php echo "Email: $email" ?></label>
    <label><?php echo "Parking points: $userPoints" ?></label>
    <label><?php echo "Status: $userStatus" ?></label>
    <label><?php if (!empty($parkingSpot)) {
                $zone = $parkingSpot['zone'];
                $parkNumber = $parkingSpot['number'];
                echo "The user is parked on parking spot: $zone $parkNumber";
            } else {
                echo "The user is not in the parking now!";
            } ?></label>
</body>

</html>