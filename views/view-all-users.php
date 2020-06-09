<?php

include '../configuration/db_config.php';
include '../utils/tableNames.php';
include '../utils/utils.php';
include '../views/menu.php';
?>
<?php if (isLoggedInUser()) {
    $table = TableNames::USERS;
    $sql = "SELECT * FROM $table;";

    $connection = getDatabaseConnection();
    $resultSet = $connection->prepare($sql);
    $resultSet->execute() or die("Failed to query from DB!");
?>

    <label><?php echo "The registred users in the system are: <br>" ?></label>
    <table class="table">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Firstname</th>
                <th>Lastname</th>
            </tr>
        </thead>
        <tbody>

            <?php
            while ($users = $resultSet->fetch(PDO::FETCH_ASSOC)) {
                $currentUserId = $users['user_id'];
                $currentUserPhotoPath = Utils::USER_PHOTO_FOLDER_PATH . $users['photo_name'];
            ?>
                <tr>
                    <td><img src="<?php echo $currentUserPhotoPath ?>"></td>
                    <td><?= $users['first_name'] ?></td>
                    <td><?= $users['last_name'] ?></td>
                </tr>
            <?php }
        } else { ?>
            <label><?php echo "You should br logged in!" ?></label>
        <?php } ?>