<?php
include '../utils/utils.php';
include '../utils/databaseQueriesUtils.php';
include '../views/menu.php';

if (isLoggedInUser()) {
    $email = $_SESSION["email"];
    $user = DatabaseQueriesUtils::getUserByEmail($email);

    $userId = $user['user_id'];
    $userPoints = $user['points'];
    $userStatus = $user['status'];
    $userStatusLowerCase = ucfirst(strtolower($userStatus));
    $userPhotoName = $user['photo_name'];
    $userPhotoPath = Utils::USER_PHOTO_FOLDER_PATH . $userPhotoName;

    $viewers = DatabaseQueriesUtils::getUserProfileViewers($userId);
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
                <img src="<?php echo $userPhotoPath ?>">
                <div class="info-wrapper">
                    <h2>My info</h2>
                    <label><?php echo "Status: $userStatusLowerCase" ?></label>
                    <label><?php echo "Parking points: $userPoints" ?></label>
                </div>
            </div>
            <h4><?php echo "My profile was viewed by the following users: <br>" ?></h4>
            <table class="table-style">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>View time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($viewers as $viewer) { ?>
                        <tr>
                            <td><?= $viewer['first_name'] . ' ' . $viewer['last_name'] ?></td>
                            <td><?= $viewer['email'] ?></td>
                            <td><?= $viewer['view_time'] ?></td>
                        </tr>
                    <?php }
                } else { ?>
                    <label><?php echo "You should br logged in!" ?></label>
                <?php } ?>
        </div>
    </body>

    </html>