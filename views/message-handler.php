<?php
if (!isset($_REQUEST['message']) || !isset($_REQUEST['isSuccess'])) {
    return;
}
$message = $_REQUEST['message'];
$isSuccess = $_REQUEST['isSuccess'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <link rel="stylesheet" type="text/css" href="../styles/fmi-parking-style.css">
    <link rel="stylesheet" type="text/css" href="../styles/message-handler-style.css">
    <title>Error</title>
</head>

<body>
    <?php
    include '../views/menu.php';
    if ($isSuccess) { ?>
        <div class="alert-success">
            <strong>Success!</strong> <?php echo $message ?>
        </div>
    <?php } else { ?>
        <div class="alert-error">
            <strong>Error!</strong> <?php echo $message ?>
        </div>
    <?php } ?>
</body>

</html>