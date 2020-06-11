<!DOCTYPE html>
<html>

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <link rel="stylesheet" type="text/css" href="../styles/scheduler.css">
    <title>Scheduler</title>
</head>

<body>
    <?php
    include '../views/menu.php';
    ?>
    <form method="POST" action="../controllers/scheduler.php">
    <div class="content">
        <label for="myfile">Add new scheduler:</label>
        <input type="file" name="scheduler" id="scheduler" accept="application/JSON" required>
        <input type="submit" id="upload-scheduler-button" value="Upload sheduler" name="submit">
    </div>
    </form>
</body>

</html>