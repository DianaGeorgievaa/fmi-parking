<?php
include '../fmi_parking/utils/utils.php';
include '../configuration/db_config.php';
include '../utils/tableNames.php';

if (isLoggedInUser()) {
    $userQrCode = $_REQUEST['userQrCode'];
    $user = getUserByQRCode($userQrCode);
    $status = $user['status'];
    $userId = $user['status'];

    $userParkingInfo = getUserParkingInfo($userId);
    if (!empty($userParkingInfo)) {
        $parkingDateIn = $userParkingInfo['parking_date_in'];
        updateUserPoints($userId, $parkingDateIn, $status);
        deleteUserParkingInfo($userId);
        return;
    }

    if ($status == 'BLOCKED') {
        // Your entrance is blocked!
        return;
    }

    $numberOfFreeParkingSpots = getFreeParkingSpotsNumber();

    if ($status == 'PERMANENT') {
        if ($numberOfFreeParkingSpots == 0) {
            // There are no free parking spots
            // TODO show after how minutes the parking will be free
            return;
        }
        saveUserParkingInfo($userId);
    } else if ($status == 'TEMPORARY') {
        if ($numberOfFreeParkingSpots == 0) {
            // There are no free parking spots
            // TODO show after how minutes the parking will be free
            return;
        }

        $courseIds = getUserCourseIds($userId);
        $courses = getUserCourses($courseIds);
        $lecture = getLectureAtThatTime($courses);
        if ($lecture != "") {
            saveUserParkingInfo($userId);
        } else if ($numberOfFreeParkingSpots > Utils::REQUIRED_PARKING_SPOTS) {
            // You din't have lecture in the next 30 minutes but you are allowed to enter in the parking. 
            // Notification will be send if you have to leave.
            saveUserParkingInfo($userId);
        } else {
            // There are not enough parking spots
        }
    }
}

function isLoggedInUser()
{
    return isset($_SESSION['email']);
}

function getUserByQRCode($qrCode)
{
    $table = TableNames::USERS;
    $sql = "SELECT * FROM $table WHERE qr_code = :qrCode;";

    $connection = getDatabaseConnection();
    $resultSet = $connection->prepare($sql);
    $resultSet->bindParam(':qrCode', $qrCode);
    $resultSet->execute() or die("Failed to query from DB!");
    $user = $resultSet->fetch(PDO::FETCH_ASSOC) or die("Failed to get user.");

    return $user;
}

function getUserCourseIds($userId)
{
    $table = TableNames::USERS_COURSES;
    $sql = "SELECT course_id FROM $table WHERE user_id = :userId;";

    $connection = getDatabaseConnection();
    $resultSet = $connection->prepare($sql);
    $resultSet->bindParam(':userId', $userId);
    $resultSet->execute() or die("Failed to query from DB!");
    $courseIds = $resultSet->fetch(PDO::FETCH_ASSOC) or die("Failed to get courses.");

    return $courseIds;
}

function getUserCourses($courseIds)
{

    $table = TableNames::COURSES;
    $sql = "SELECT * FROM $table WHERE course_id = :courseId;";

    $connection = getDatabaseConnection();
    $resultSet = $connection->prepare($sql);

    $userCourses = array();
    foreach ($courseIds as $course) {
        $resultSet->bindParam(':userId', $course['course_id']);
        $resultSet->execute() or die("Failed to query from DB!");
        $currentCourse = $resultSet->fetch(PDO::FETCH_ASSOC) or die("Failed to get courses.");
        $userCourses[] = $currentCourse;
    }

    return $userCourses;
}

function getFreeParkingSpotsNumber()
{
    $table = TableNames::PARKING_SPOT;
    $sql = "SELECT COUNT(*) FROM $table WHERE is_free = 1;";

    $connection = getDatabaseConnection();
    $resultSet = $connection->prepare($sql);
    $resultSet->execute() or die("Failed to query from DB!");
    $numberOfFreeParkingSpots = $resultSet->fetch(PDO::FETCH_ASSOC) or die("Failed to get courses.");

    return $numberOfFreeParkingSpots;
}

function saveUserParkingInfo($userId)
{
    $table = TableNames::USER_PARKING_INFO;
    $sql = "INSERT INTO $table (parking_date_in, user_id) 
                   VALUES (:parkingDateIn, :userId);";

    $currentDate = date("Y-m-d H:i:s");
    $connection = getDatabaseConnection();
    $resultSet = $connection->prepare($sql);
    $resultSet->bindParam(':parkingDateIn', $currentDate);
    $resultSet->bindParam(':userId', $userId);
    $resultSet->execute() or die("Failed to query from DB!");
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

function getUserParkingInfo($userId)
{
    $table = TableNames::USER_PARKING_INFO;
    $sql = "SELECT * FROM $table WHERE user_id = :userId;";

    $connection = getDatabaseConnection();
    $resultSet = $connection->prepare($sql);
    $resultSet->bindParam(':userId', $userId);
    $resultSet->execute() or die("Failed to query from DB!");
    $userParkingInfo = $resultSet->fetch(PDO::FETCH_ASSOC) or die("Failed to get courses.");

    return $userParkingInfo;
}

function deleteUserParkingInfo($userId)
{
    $table = TableNames::USER_PARKING_INFO;
    $sql = "DELETE FROM $table WHERE user_id = :userId;";

    $connection = getDatabaseConnection();
    $resultSet = $connection->prepare($sql);
    $resultSet->bindParam(':userId', $userId);
    $resultSet->execute() or die("Failed to query from DB!");
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
        $courseIds = getUserCourseIds($userId);
        $courses = getUserCourses($courseIds);
        $lecture = getLectureAtThatTime($courses);
        $secondTimestamp = strtotime($lecture['end_time']);
        $difference = abs($firstTimestamp - $secondTimestamp);
        if ($difference > 30) {
            $points -= 1;
        } else {
            $points += 1;
        }
    }

    $table = TableNames::USERS;
    $sql = "UPDATE $table SET points = :points WHERE user_id = :userId;";

    $connection = getDatabaseConnection();
    $resultSet = $connection->prepare($sql);
    $resultSet->bindParam(':points', $points);
    $resultSet->bindParam(':userId', $userId);
    $resultSet->execute() or die("Failed to query from DB!");
}
