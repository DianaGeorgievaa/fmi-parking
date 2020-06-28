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
    include '../utils/databaseQueriesUtils.php';
    include '../utils/emailMessages.php';
    include '../controllers/email-notification.php';
    include '../views/menu.php';
    
    if (isset($_POST["send-email"])) {
        $userId = DatabaseQueriesUtils::getUserIdWithouthLecturesFromUserParkingInfo();
        if ($userId != null) {
            $id = $userId['user_id'];
            $user = DatabaseQueriesUtils::getUserByUserId($id);
            $userEmail = $user['email'];
            EmailNotification::sendEmailNotification($userEmail, EmailMessages::EMAIL_PARKING_LEAVING_SUBJECT, EmailMessages::EMAIL_PARKING_LECTURE_BODY);
        } else {
            Utils::showMessage(MessageUtils::NO_USERS_WITHOUT_LECTURES_ERROR_MESSAGE, false);
        }
    }

    $userStatus = isset($_SESSION['status']) ? $_SESSION['status'] : "";
    if (isLoggedInUser() && $userStatus != 'ADMIN') {
        $userQRCodePath = Utils::QR_CODE_FOLDER_PATH . $_SESSION['firstName'] . $_SESSION['lastName'] . '.png'; ?>
        <div id="reader"></div>
        <div>
            <img id="qrCodeImage" src="<?php echo $userQRCodePath ?>">
            <div>
                <button id="print" class="button-style" onclick="printQRCode()">Print <i id="printer-icon">&#128424;</i></button>
                <button id="scan" class="button-style">Scan code </button>
            </div>
        </div>
    <?php } else if ($userStatus == 'ADMIN') { ?>
        <form method="POST">
            <button id="send-email-button" name="send-email" class="button-style">Send email for leaving</button>
        </form>
    <?php } ?>
</body>

</html>