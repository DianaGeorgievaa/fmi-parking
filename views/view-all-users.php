<?php

include '../configuration/db_config.php';
include '../utils/tableNames.php';
include '../utils/utils.php';
include '../views/menu.php';
?>
<?php if (isLoggedInUser()) {
    $logedinUserEmail = $_SESSION["email"];
    $table = TableNames::USERS;
    $sql = "SELECT * FROM $table WHERE email != :email;";

    $connection = getDatabaseConnection();
    $resultSet = $connection->prepare($sql);
    $resultSet->bindParam(':email', $logedinUserEmail);
    $resultSet->execute() or die("Failed to query from DB!");
?>
<!DOCTYPE html>
<html>

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <link rel="stylesheet" type="text/css" href="../styles/fmi-parking-style.css">
    <link rel="stylesheet" type="text/css" href="../styles/view-all-users.css">
    <script type="text/javascript" src="../js/user-profile-redirection.js"></script>
    <title>User profile</title>
</head>

<body>
    <div class="all-users-wrapper">
        <h3><?php echo "The registred users in the system are: <br>" ?></h3>
        <table class="table-style">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($users = $resultSet->fetch(PDO::FETCH_ASSOC)) {
                    $currentUserId = $users['user_id'];
                    $currentUserPhotoPath = Utils::USER_PHOTO_FOLDER_PATH . $users['photo_name'];
                ?>
                    <tr <?php echo "onclick='showUserProfile(".$currentUserId.")'" ?>>
                        <td><img id="user-image" src="<?php echo $currentUserPhotoPath ?>"></td>
                        <td><?= $users['first_name'] . ' ' . $users['last_name'] ?></td>
                    </tr>
                <?php }
            } else { ?>
                <label><?php echo "You should be logged in!" ?></label>
            <?php } ?>
    </div>
</body>

</html>