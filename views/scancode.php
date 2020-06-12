<?php include_once '../views/menu.php'; ?>

<!DOCTYPE html>
<html>

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <link rel="stylesheet" type="text/css" href="../styles/fmi-parking-style.css">
    <link rel="stylesheet" type="text/css" href="../styles/scancode.css">
    <script type="module" src="../js/qr-scanner-video.js"></script>
    <title>Scan Code</title>
</head>

<body>
    <?php
    if (isLoggedInUser()) { ?>
        <div class="scan-code-wrapper">
            <b>Device has camera: </b>
            <span id="device-has-camera"></span>
            <br>
            <video muted playsinline id="qr-video"></video>
            <div>
                <select id="inversion-mode-select">
                    <option value="original">Scan original (dark QR code on bright background)</option>
                    <option value="invert">Scan with inverted colors (bright QR code on dark background)</option>
                    <option value="both">Scan both</option>
                </select>
            </div>
        </div>
        </div>
    <?php } ?>
</body>

</html>