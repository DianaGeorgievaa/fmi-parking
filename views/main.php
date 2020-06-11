<!DOCTYPE html>
<html>

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <link rel="stylesheet" type="text/css" href="../styles/main.css">
    <script src="../js/print-qr-code.js"></script>
    <title>FMI Parking</title>
</head>

<body>

    <?php
    include '../utils/utils.php';
    include '../views/menu.php';

    if (isLoggedInUser()) {
        $userQRCodePath = Utils::QR_CODE_FOLDER_PATH . $_SESSION['firstName'] . $_SESSION['lastName'] . '.png'; ?>
        <div>
            <img id="qrCode" src="<?php echo $userQRCodePath ?>">
            <button onclick="printQRCode()">Print <i>&#128424;</i></button>
        </div>
    <?php } ?>
</body>

</html>