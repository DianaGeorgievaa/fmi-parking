<!DOCTYPE html>
<html>

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <script src="../js/print-qr-code.js"></script>
    <title>FMI Parking</title>
</head>

<body>

    <?php
    include '../utils/utils.php';
    include '../views/menu.php';

    if (isLoggedInUser()) {
        $userQRCodePath = Utils::QR_CODE_FOLDER_PATH . $_SESSION['firstName'] . $_SESSION['lastName'] . '.png'; ?>

        <img id="qrCode" src="<?php echo $userQRCodePath ?>">
        <button onclick="printQRCode()">Print</button>
    <?php } ?>
</body>

</html>