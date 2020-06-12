<?php
include '../utils/tableNames.php';
include '../utils/utils.php';
include '../utils/databaseQueriesUtils.php';
include '../views/menu.php';

if (isLoggedInUser()) {
    $limit = 3;
    $topUsers = DatabaseQueriesUtils::getTopUsers($limit);
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
        <link rel="stylesheet" type="text/css" href="../styles/fmi-parking-style.css">
        <link rel="stylesheet" type="text/css" href="../styles/top-users.css">
        <script type="text/javascript" src="../js/user-profile-redirection.js"></script>
        <title>My profile</title>
    </head>

    <body>
        <div class="top-users-wrapper">
            <h2><?php echo "The top 3 users by parking points are: <br>" ?></h2>
            <table class="table-style">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($topUsers as $user) {
                        $currentUserId = $user['user_id'];
                    ?>
                        <tr <?php echo "onclick='showUserProfile(" . $currentUserId . ")'" ?>>
                            <td><?= $user['first_name'] . ' ' . $user['last_name'] ?></td>
                            <td><?= $user['points'] ?></td>
                        </tr>
                    <?php }
                } else { ?>
                    <label><?php echo "You should br logged in!" ?></label>
                <?php } ?>
        </div>
    </body>

    </html>