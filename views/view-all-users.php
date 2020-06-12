<?php
include '../utils/databaseQueriesUtils.php';
include '../views/menu.php';

if (isLoggedInUser()) {
    $logedinUserEmail = $_SESSION["email"];
    $users = DatabaseQueriesUtils::getUsersWithouthLoggedinUser($logedinUserEmail);
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
                foreach ($users as $user) {
                    $currentUserId = $user['user_id'];
                    $currentUserPhotoPath = Utils::USER_PHOTO_FOLDER_PATH . $user['photo_name'];
                ?>
                    <tr <?php echo "onclick='showUserProfile(".$currentUserId.")'" ?>>
                        <td><img id="user-image" src="<?php echo $currentUserPhotoPath ?>"></td>
                        <td><?= $user['first_name'] . ' ' . $user['last_name'] ?></td>
                    </tr>
                <?php }
            } else { ?>
                <label><?php echo "You should be logged in!" ?></label>
            <?php } ?>
    </div>
</body>

</html>