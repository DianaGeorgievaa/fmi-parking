<?php

include '../configuration/db_config.php';
include '../utils/tableNames.php';
include '../utils/utils.php';
include '../views/menu.php';

function getLoggedInUser()
{
    $table = TableNames::USERS;
    $sql = "SELECT * FROM $table WHERE email = :email;";
    $email = $_SESSION["email"];

    $connection = getDatabaseConnection();
    $resultSet = $connection->prepare($sql);
    $resultSet->bindParam(':email', $email);
    $resultSet->execute() or die("Failed to query from DB!");
    $user = $resultSet->fetch(PDO::FETCH_ASSOC) or die("Failed to get user.");

    return $user;
}

if (!isset($_SESSION)) {
    session_start();
}

?>
<?php if (isLoggedInUser()) {
    $user = getLoggedInUser();
    $userId = $user['user_id'];
    $userPoints = $user['points'];
    $userStatus = $user['status'];
    $userStatusLowerCase = ucfirst(strtolower($userStatus));
    $photoName = $user['photo_name'];
    $photoPath = Utils::USER_PHOTO_FOLDER_PATH . $photoName;
?>
<!DOCTYPE html>
<html>

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <link rel="stylesheet" type="text/css" href="../styles/fmi-parking-style.css">
    <link rel="stylesheet" type="text/css" href="../styles/my-profile.css">
    <title>My profile</title>
</head>

<body>
    <div class="my-profile-wrapper">
        <div class="user-info-wrapper">
            <img src="<?php echo $photoPath ?>">
            <div class="info-wrapper">
                <h2>My info</h2>
                <label><?php echo "Status: $userStatusLowerCase" ?></label>
                <label><?php echo "Parking points: $userPoints" ?></label>
            </div>
        </div>
    
        <?php
        $table = TableNames::PROFILE_VIEWER;
        $sql = "SELECT * FROM $table WHERE user_id = :userId;;";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->bindParam(':userId', $userId);
        $resultSet->execute() or  die("Failed to query from DB!");
        ?>

        <h4><?php echo "My profile was viewed by the following users: <br>" ?></h4>
        <table class="table-style">
            <thead>
                <tr>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Email</th>
                    <th>View time</th>
                </tr>
            </thead>
            <tbody>

                <?php
                while ($row = $resultSet->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?= $row['first_name'] ?></td>
                        <td><?= $row['last_name'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['view_time'] ?></td>
                    </tr>
                <?php }
            } else { ?>
                <label><?php echo "You should br logged in!" ?></label>
            <?php } ?>
        </div>
</body>

</html>