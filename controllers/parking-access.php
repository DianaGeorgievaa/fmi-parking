<?php
include '../utils/databaseQueriesUtils.php';
include '../utils/emailMessages.php';
include '../controllers/email-notification.php';

if (!isset($_SESSION)) {
    session_start();
}

if (isLoggedInUser()) {
    $userQrCode = $_REQUEST['userQrCode'];
    $user = DatabaseQueriesUtils::getUserByQRCode($userQrCode);
    $status = $user['status'];
    $userId = $user['user_id'];
    $userEmail = $user['email'];

    /**
     * If userParkingInfo is not empty the user exits from the parking
     */
    $userParkingInfo = DatabaseQueriesUtils::getUserParkingInfo($userId);
    if ($userParkingInfo != null) {
        $parkingDateIn = $userParkingInfo['parking_date_in'];
        updateUserPoints($userId, $parkingDateIn, $status);
        DatabaseQueriesUtils::deleteUserParkingInfo($userId);
        DatabaseQueriesUtils::updateUserParkingSpot($userEmail);
        header('Location:' . '../views/main.php');
        return;
    }

    if ($status == 'BLOCKED') {
        Utils::showMessage(MessageUtils::BLOCKED_ENTRANCE_MESSAGE, false);
    }

    $sqlResult = DatabaseQueriesUtils::getFreeParkingSpotsNumber();
    $numberOfFreeParkingSpots = null;
    if ($sqlResult != null) {
        $numberOfFreeParkingSpots = $sqlResult['parking_spot_number'];
    }

    if ($status == 'PERMANENT') {
        if ($numberOfFreeParkingSpots == null || $numberOfFreeParkingSpots == 0) {
            // TODO show after how minutes the parking will be free
            Utils::showMessage(MessageUtils::NOT_FREE_PAKING_SPOTS_MESSAGE, false);
            return;
        }
        DatabaseQueriesUtils::saveUserWithLectureParkingInfo($userId, 1, null);
        header('Location:' . '../views/parking-schema.php');
    } else if ($status == 'TEMPORARY') {
        if ($numberOfFreeParkingSpots == null || $numberOfFreeParkingSpots == 0) {
            // TODO show after how minutes the parking will be free
            Utils::showMessage(MessageUtils::NOT_FREE_PAKING_SPOTS_MESSAGE, false);
            return;
        }

        $courseIds = DatabaseQueriesUtils::getUserCourseIds($userId);
        $courses = DatabaseQueriesUtils::getUserCourses($courseIds);
        $lecture = getLectureAtThatTime($courses);
        if ($lecture != null) {
            $endTimeLecture = $lecture['end_time'];
            DatabaseQueriesUtils::saveUserWithLectureParkingInfo($userId, 1, $endTimeLecture);
            $body = EmailMessages::EMAIL_PARKING_LEAVING_BODY . $lecture['end_time'];
            EmailNotification::sendEmailNotification($userEmail, EmailMessages::EMAIL_PARKING_LEAVING_SUBJECT, $body);
            header('Location:' . '../views/parking-schema.php');
        } else if ($numberOfFreeParkingSpots > Utils::REQUIRED_PARKING_SPOTS) {
            DatabaseQueriesUtils::saveUserWithoutLectureParkingInfo($userId, 0, null);
            Utils::showMessage(MessageUtils::PARKING_ENTRANCE_WARNING_MESSAGE, false);
        } else {
            Utils::showMessage(MessageUtils::NOT_ENOUGHT_PAKING_SPOTS_MESSAGE, false);
        }
    }
}

function isLoggedInUser()
{
    return isset($_SESSION['email']);
}

function updateUserPoints($userId, $parkingDateIn, $status)
{
    if ($status == 'PERMANENT') {
        updatePermanentUserPoints($userId, $parkingDateIn);
    } else if ($status == 'TEMPORARY') {
        updateTemporaryUserPoints($userId);
    }
}

function getLectureAtThatTime($courses)
{
    $currentDate = date("H:i:s");
    $currentWeekDay = strtoupper(date("l"));
    $currentDateTimestamp = strtotime($currentDate) + 3600;
    foreach ($courses as $course) {
        $courseDay = $course['course_day'];
        $courseStartTime = $course['start_time'];
        $startTimeCourseTimestamp = strtotime($courseStartTime);
        $difference = abs($currentDateTimestamp - $startTimeCourseTimestamp);

        if ($difference / 60 <= 30 && $currentWeekDay == $courseDay) {
            return $course;
        }
    }

    return null;
}

function updatePermanentUserPoints($userId, $parkingDateIn)
{
    $sqlResult = DatabaseQueriesUtils::getUserPoints($userId);
    $points = $sqlResult['points'];
    $currentDate = strtotime(date("Y-m-d H:i:s"));
    $dateIn = strtotime($parkingDateIn) + 3600;
    $datediff = $currentDate - $dateIn;
    $difference = floor($datediff / (60 * 60 * 24));
    if ($difference > 1) {
        $points -= 1;
    } else {
        $points += 1;
    }
    DatabaseQueriesUtils::updateUserPoints($userId, $points);
}

function updateTemporaryUserPoints($userId)
{
    $userParkingInfo = DatabaseQueriesUtils::getUserParkingInfo($userId);
    if ($userParkingInfo['has_lectures']) {
        $sqlResult = DatabaseQueriesUtils::getUserPoints($userId);
        $points = $sqlResult['points'];
        $currentDate = date("H:i:s");
        $firstTimestamp = strtotime($currentDate) + 3600;
        $secondTimestamp = strtotime($userParkingInfo['end_time_lecture']);
        $difference = abs($firstTimestamp - $secondTimestamp);
        if ($difference / 60 > 30) {
            $points -= 1;
        } else {
            $points += 1;
        }
        DatabaseQueriesUtils::updateUserPoints($userId, $points);
    }
}
