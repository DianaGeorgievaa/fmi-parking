<?php

include '../configuration/db_config.php';
include '../utils/tableNames.php';
include '../utils/utils.php';
include '../views/menu.php';
?>
<?php if (isLoggedInUser()) {
    $table = TableNames::USERS;
    $sql = "SELECT * FROM $table ORDER BY points DESC LIMIT 3;";

    $connection = getDatabaseConnection();
    $resultSet = $connection->prepare($sql);
    $resultSet->execute() or die("Failed to query from DB!");
?>
<!DOCTYPE html>
<html>

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <link rel="stylesheet" type="text/css" href="../styles/top-users.css">
    <script type="text/javascript" src="../js/user-profile-redirection.js"></script>
    <title>My profile</title>
</head>

<body>
    <div class="content">
        <h2><?php echo "The top 3 users by parking points are: <br>" ?></h2>
        <table id="top-users">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Points</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($users = $resultSet->fetch(PDO::FETCH_ASSOC)) {
                    $currentUserId = $users['user_id'];
                ?>
                    <tr <?php echo "onclick='showUserProfile(".$currentUserId.")'" ?>>
                        <td><?= $users['first_name'] . ' ' . $users['last_name'] ?></td>
                        <td><?= $users['points'] ?></td>
                    </tr>
                <?php }
            } else { ?>
                <label><?php echo "You should br logged in!" ?></label>
            <?php } ?>
    </div>
</body>

</html>