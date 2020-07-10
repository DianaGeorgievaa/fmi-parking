<!DOCTYPE html>
<html>

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <link rel="stylesheet" type="text/css" href="../styles/fmi-parking-style.css">
    <link rel="stylesheet" type="text/css" href="../styles/schedule.css">
    <title>Schedule</title>
</head>

<body>
    <?php
    include '../views/menu.php';
    ?>
    <form method="POST" action="../controllers/schedule.php" enctype="multipart/form-data">
    <div class="schedule-wrapper">
        <input type="file" name="schedule" id="schedule-input" accept="application/JSON">
        <input type="submit" id="upload-schedule-button" class="button-style" value="Upload shedule" name="upload-schedule">
        <div id="delete-button-wrapper">
            <input type="submit" id="delete-schedule-button" class="button-style" value="Delete shedule" name="delete-schedule">
        </div>
    </div>
    </form>
</body>

</html>