<!DOCTYPE html>
<html>

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <link rel="stylesheet" type="text/css" href="../styles/fmi-parking-style.css">
    <link rel="stylesheet" type="text/css" href="../styles/main.css">
    <script src="../js/print-qr-code.js"></script>
    <script type="module" src="../js/qr-scanner-image.js"></script>
    <title>FMI Parking</title>
</head>

<body>

    <?php
    include '../utils/utils.php';
    include '../views/menu.php';

    if (isLoggedInUser()) {
        $userQRCodePath = Utils::QR_CODE_FOLDER_PATH . $_SESSION['firstName'] . $_SESSION['lastName'] . '.png'; ?>
        <div id="reader"></div>
        <div>
            <img id="qrCodeImage" src="<?php echo $userQRCodePath ?>">
            <div>
                <button id="print" class="button-style" onclick="printQRCode()">Print <i id="printer-icon">&#128424;</i></button>
                <button id="scan" class="button-style">Scan code </button>
            </div>
        </div>
    <?php } ?>

</body>

</html>