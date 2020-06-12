<?php
include '../fmi_parking/utils/utils.php';
include '../utils/tableNames.php';
include '../utils/databaseQueriesUtils.php';

if (isLoggedInUser()) {
    $userQrCode = $_REQUEST['userQrCode'];
    $user = DatabaseQueriesUtils::getUserByQRCode($userQrCode);
    $status = $user['status'];
    $userId = $user['status'];

    $userParkingInfo = DatabaseQueriesUtils::getUserParkingInfo($userId);
    if ($userParkingInfo != "") {
        $parkingDateIn = $userParkingInfo['parking_date_in'];
        DatabaseQueriesUtils::updateUserPoints($userId, $parkingDateIn, $status);
        DatabaseQueriesUtils::deleteUserParkingInfo($userId);
        return;
    }

    if ($status == 'BLOCKED') {
        // Your entrance is blocked!
        return;
    }

    $numberOfFreeParkingSpots = DatabaseQueriesUtils::getFreeParkingSpotsNumber();

    if ($status == 'PERMANENT') {
        if ($numberOfFreeParkingSpots == 0) {
            // There are no free parking spots
            // TODO show after how minutes the parking will be free
            return;
        }
        DatabaseQueriesUtils::saveUserParkingInfo($userId);
    } else if ($status == 'TEMPORARY') {
        if ($numberOfFreeParkingSpots == 0) {
            // There are no free parking spots
            // TODO show after how minutes the parking will be free
            return;
        }

        $courseIds = DatabaseQueriesUtils::getUserCourseIds($userId);
        $courses = DatabaseQueriesUtils::getUserCourses($courseIds);
        $lecture = DatabaseQueriesUtils::getLectureAtThatTime($courses);
        if ($lecture != "") {
            DatabaseQueriesUtils::saveUserParkingInfo($userId);
        } else if ($numberOfFreeParkingSpots > Utils::REQUIRED_PARKING_SPOTS) {
            // You din't have lecture in the next 30 minutes but you are allowed to enter in the parking. 
            // Notification will be send if you have to leave.
            DatabaseQueriesUtils::saveUserParkingInfo($userId);
        } else {
            // There are not enough parking spots
        }
    }
}

function isLoggedInUser()
{
    return isset($_SESSION['email']);
}