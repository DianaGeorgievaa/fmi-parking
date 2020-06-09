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

    <label><?php echo "The top 3 users by parking points are: <br>" ?></label>
    <table class="table">
        <thead>
            <tr>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>

            <?php
            while ($users = $resultSet->fetch(PDO::FETCH_ASSOC)) {
            ?>
                <tr>
                    <td><?= $users['first_name'] ?></td>
                    <td><?= $users['last_name'] ?></td>
                    <td><?= $users['points'] ?></td>
                </tr>
            <?php }
        } else { ?>
            <label><?php echo "You should br logged in!" ?></label>
        <?php } ?>