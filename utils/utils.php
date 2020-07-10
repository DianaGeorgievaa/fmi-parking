<?php
class Utils
{
    const QR_CODE_FOLDER_PATH = '../QRCodes/';
    const USER_PHOTO_FOLDER_PATH = '../userPhotos/';
    const REQUIRED_PARKING_SPOTS = 10;

    const STATUS = [
        'Admin' => 'ADMIN',
        'Permanent' => 'PERMANENT',
        'Temporary' => 'TEMPORARY',
        'Blocked' => 'BLOCKED'
    ];

    const DAYS_OF_WEEK = [
        'Monday' => 'MONDAY',
        'Tuesday' => 'TUESDAY',
        'Wednesday' => 'WEDNESDAY',
        'Thursday' => 'THURSDAY',
        'Friday' => 'FRIDAY',
        'Saturday' => 'SATURDAY',
        'Sunday' => 'SUNDAY',
    ];

    public static function showMessage($message, $isSuccess)
    {
        header("Location:" . '../views/message-handler.php' . '?message=' . $message . '&isSuccess=' . $isSuccess);
        die();
    }

    public static function showWarningMessage($message, $isWarning)
    {
        header("Location:" . '../views/message-handler.php' . '?message=' . $message . '&isWarning=' . $isWarning);
        die();
    }
}
