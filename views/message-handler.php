<?php
if ((!isset($_REQUEST['message']) && !isset($_REQUEST['isSuccess'])) || (!isset($_REQUEST['message']) && !isset($_REQUEST['isWarning']))) {
    return;
}
$message = $_REQUEST['message'];

$isSuccess = false;
if (isset($_REQUEST['isSuccess'])) {
    $isSuccess = $_REQUEST['isSuccess'];
}
$isWarning = false;
if (isset($_REQUEST['isWarning'])) {
    $isWarning = $_REQUEST['isWarning'];
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <link rel="stylesheet" type="text/css" href="../styles/fmi-parking-style.css">
    <link rel="stylesheet" type="text/css" href="../styles/message-handler-style.css">
    <title>FMI Parking</title>
</head>

<body>
    <?php
    include '../views/menu.php';
    if ($isSuccess) { ?>
        <div class="alert-success">
            <strong>Success!</strong> <?php echo $message ?>
        </div>
    <?php } elseif ($isWarning) { ?>
        <div class="alert-warning">
            <?php echo $message ?>
            <a href="../views/parking-schema.php" id="parking-spot-redirection">Choose parking spot</a>
        </div>
    <?php } else { ?>
        <div class="alert-error">
            <strong>Error!</strong> <?php echo $message ?>
        </div>
    <?php } ?>
</body>

</html>