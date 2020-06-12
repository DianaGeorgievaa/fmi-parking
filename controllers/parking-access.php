<?php
include '../utils/databaseQueriesUtils.php';

if (isLoggedInUser()) {
    $userQrCode = $_REQUEST['userQrCode'];
    $user = DatabaseQueriesUtils::getUserByQRCode($userQrCode);
    $status = $user['status'];
    $userId = $user['status'];

    /**
     * If userParkingInfo is not empty the user is exits from the parking
     */
    $userParkingInfo = DatabaseQueriesUtils::getUserParkingInfo($userId);
    if ($userParkingInfo != "") {
        $parkingDateIn = $userParkingInfo['parking_date_in'];
        DatabaseQueriesUtils::updateUserPoints($userId, $parkingDateIn, $status);
        DatabaseQueriesUtils::deleteUserParkingInfo($userId);
        return;
    }

    if ($status == 'BLOCKED') {
        Utils::showMessage(MessageUtils::BLOCKED_ENTRANCE_MESSAGE, false);
    }

    $numberOfFreeParkingSpots = DatabaseQueriesUtils::getFreeParkingSpotsNumber();

    if ($status == 'PERMANENT') {
        if ($numberOfFreeParkingSpots == 0) {
            // TODO show after how minutes the parking will be free
            Utils::showMessage(MessageUtils::NOT_FREE_PAKING_SPOTS_MESSAGE, false);
        }
        DatabaseQueriesUtils::saveUserParkingInfo($userId);
    } else if ($status == 'TEMPORARY') {
        if ($numberOfFreeParkingSpots == 0) {
            // TODO show after how minutes the parking will be free
            Utils::showMessage(MessageUtils::NOT_FREE_PAKING_SPOTS_MESSAGE, false);
        }

        $courseIds = DatabaseQueriesUtils::getUserCourseIds($userId);
        $courses = DatabaseQueriesUtils::getUserCourses($courseIds);
        $lecture = getLectureAtThatTime($courses);
        if ($lecture != "") {
            DatabaseQueriesUtils::saveUserParkingInfo($userId);
        } else if ($numberOfFreeParkingSpots > Utils::REQUIRED_PARKING_SPOTS) {
            DatabaseQueriesUtils::saveUserParkingInfo($userId);
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
    $points = 0;
    $currentDate = strtotime(date("Y-m-d H:i:s"));
    if ($status == 'PERMANENT') {
        $dateIn = strtotime($parkingDateIn);
        $datediff = $currentDate - $dateIn;
        $difference = floor($datediff / (60 * 60 * 24));
        if ($difference > 1) {
            $points -= 1;
        } else {
            $points += 1;
        }
    } else if ($status == 'TEMPORARY') {
        $firstTimestamp = strtotime($currentDate);
        $courseIds = DatabaseQueriesUtils::getUserCourseIds($userId);
        $courses = DatabaseQueriesUtils::getUserCourses($courseIds);
        $lecture = getLectureAtThatTime($courses);
        $secondTimestamp = strtotime($lecture['end_time']);
        $difference = abs($firstTimestamp - $secondTimestamp);
        if ($difference > 30) {
            $points -= 1;
        } else {
            $points += 1;
        }
    }
    DatabaseQueriesUtils::updateUserPoints($userId);
}

function getLectureAtThatTime($courses)
{
    $currentDate = date("Y-m-d H:i:s");
    $currentWeekDay = strtoupper(date("l"));
    $currentDateTimestamp = strtotime($currentDate);
    foreach ($courses as $course) {
        $courseDay = $course['course_day'];
        $startTimeCourseTimestamp = strtotime($course['start_time']);
        $difference = abs($currentDateTimestamp - $startTimeCourseTimestamp);
        if ($difference <= 30 && $currentWeekDay == $courseDay) {
            return $course;
        }
    }

    return "";
}
