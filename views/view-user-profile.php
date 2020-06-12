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
    if(empty($parkingSpotInfo)){
        return "";
    }
    return $resultSet->fetch(PDO::FETCH_ASSOC) or die("Failed to get user parking info.");
}

function getParkingSpot($parkingSpotId)
{
    $table = TableNames::PARKING_SPOT;
    $sql = "SELECT * FROM $table WHERE parking_spot_id = :parkingSpotId;";

    $connection = getDatabaseConnection();
    $resultSet = $connection->prepare($sql);
    $resultSet->bindParam(':parkingSpotId', $parkingSpotId);
    $parkingSpot = $resultSet->execute() or die("Failed to query from DB!");
    if(empty($parkingSpot)){
        return "";
    }
    return $resultSet->fetch(PDO::FETCH_ASSOC) or die("Failed to get user.");
}

function saveViewer($viewedUserId)
{
    $table = TableNames::PROFILE_VIEWER;
    $sql = "INSERT INTO $table (first_name, last_name, email, view_time, user_id) 
                            VALUES (:firstname, :lastname, :email, :viewTime, :userId);";

    $viewerFirstname = $_SESSION['firstName'];
    $viewerLastname = $_SESSION['lastName'];
    $viewerEmail = $_SESSION["email"];
    $viewTime = date("Y-m-d H:i:s");

    $connection = getDatabaseConnection();
    $resultSet = $connection->prepare($sql);
    $resultSet->bindParam(':firstname', $viewerFirstname);
    $resultSet->bindParam(':lastname', $viewerLastname);
    $resultSet->bindParam(':email', $viewerEmail);
    $resultSet->bindParam(':viewTime', $viewTime);
    $resultSet->bindParam(':userId', $viewedUserId);
    $resultSet->execute() or die("Failed to query from DB!");
}

if (isLoggedInUser()) {
    $viewedUserId = $_REQUEST['viewedUserId'];
    $user = getUser($viewedUserId);
    $firstname = $user['first_name'];
    $lastname = $user['last_name'];
    $email = $user['email'];
    $userPoints = $user['points'];
    $userStatus = $user['status'];
    $userStatusLowerCase = ucfirst(strtolower($userStatus));
    $photoName = $user['photo_name'];
    $photoPath = Utils::USER_PHOTO_FOLDER_PATH . $photoName;

    saveViewer($viewedUserId);

    $parkingSpotId = getUserParkingInfoId($viewedUserId);
    $parkingSpot = "";
    if($parkingSpotId != ""){
        $parkingSpot = getParkingSpot($parkingSpotId);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <link rel="stylesheet" type="text/css" href="../styles/fmi-parking-style.css">
    <link rel="stylesheet" type="text/css" href="../styles/view-user-profile.css">
    <title>User profile</title>
</head>

<body>
    <div class="user-wrapper">
        <div class="user-wrapper-info">
            <img id="user-image" src="<?php echo $photoPath ?>">
            <div class="wrapper-info">
                <h2>User info</h2>
                <label><?php echo "Name: $firstname $lastname" ?></label>
                <label><?php echo "Email: $email" ?></label>
                <label><?php echo "Parking points: $userPoints" ?></label>
                <label><?php echo "Status: $userStatusLowerCase" ?></label>
                <label><?php if ($parkingSpot != "") {
                            $zone = $parkingSpot['zone'];
                            $parkNumber = $parkingSpot['number'];
                            echo "The user is parked on parking spot: $zone $parkNumber";
                        } else {
                            echo "<b> The user is not in the parking now! </b>";
                        } ?></label>
            </div>
        </div>
    </div>
</body>

</html>