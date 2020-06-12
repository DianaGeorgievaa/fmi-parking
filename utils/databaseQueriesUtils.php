<?php
include '../configuration/db_config.php';
include '../utils/utils.php';
include '../utils/messageUtils.php';
include '../models/user.php';
include '../models/lecturer.php';
include '../models/course.php';
include '../utils/tableNames.php';

class DatabaseQueriesUtils
{
    public static function saveUser($user)
    {
        $table = TableNames::USERS;
        $sql = "INSERT INTO $table (first_name, last_name, email, password, status, photo_name, points, qr_code) 
                        VALUES (:firstname, :lastname, :email, :password, :status, :photoName, :points, :qrCode);";

        $connection = getDatabaseConnection();
        $preparedSql = $connection->prepare($sql);
        $preparedSql->bindParam(':firstname', $user->getFirstName());
        $preparedSql->bindParam(':lastname', $user->getLastName());
        $preparedSql->bindParam(':email', $user->getEmail());
        $preparedSql->bindParam(':password', $user->getPassword());
        $preparedSql->bindParam(':status', $user->getStatus());
        $preparedSql->bindParam(':photoName', $user->getPhotoName());
        $preparedSql->bindParam(':points', $user->getPoints());
        $preparedSql->bindParam(':qrCode', $user->getQrCode());
        $preparedSql->execute() or Utils::showMessage(MessageUtils::DATABASE_SAVE_INFORMATION_ERROR_MESSAGE, false);
    }

    public static function saveScheduler(Course $course, $lectureId)
    {
        $connection = getDatabaseConnection();
        $table = TableNames::COURSES;
        $insertQuery = "INSERT INTO $table (course_title, course_day, start_time, end_time) 
                                VALUES (:courseTitle, :courseDay, :startTime, :endTime);";

        $preparedSql = $connection->prepare($insertQuery);
        $preparedSql->bindParam(':courseTitle', $course->getCourseTitle());
        $preparedSql->bindParam(':courseDay', $course->getCourseDay());
        $preparedSql->bindParam(':startTime', $course->getStartTime());
        $preparedSql->bindParam(':endTime', $course->getEndTime());
        $preparedSql->execute() or Utils::showMessage(MessageUtils::DATABASE_SAVE_INFORMATION_ERROR_MESSAGE, false);

        $courseId = $connection->lastInsertId();
        DatabaseQueriesUtils::insertCourseAndLecture($lectureId, $courseId);
    }

    public static function insertCourseAndLecture($lectureId, $courseId)
    {
        $connection = getDatabaseConnection();
        $table = TableNames::USERS_COURSES;
        $insertQuery = "INSERT INTO $table (course_id, user_id) 
                                VALUES (:courseId, :lectureId);";

        $preparedSql = $connection->prepare($insertQuery);
        $preparedSql->bindParam(':courseId', $courseId);
        $preparedSql->bindParam(':lectureId', $lectureId);
        $preparedSql->execute() or Utils::showMessage(MessageUtils::DATABASE_SAVE_INFORMATION_ERROR_MESSAGE, false);
    }

    public static function isExistingEmail($email)
    {
        $table = TableNames::USERS;
        $sql = "SELECT * FROM  $table WHERE email = :email;";

        $connection = getDatabaseConnection();
        $preparedSql = $connection->prepare($sql);
        $preparedSql->bindParam(':email', $email);
        $preparedSql->execute() or Utils::showMessage(MessageUtils::DATABASE_GET_INFORMATION_ERROR_MESSAGE, false);

        $result = $preparedSql->fetchAll() or Utils::showMessage(MessageUtils::GET_USER_ERROR_MESSAGE, false);;

        return count($result) != 0;
    }


    public static function getHashedPassword($email)
    {
        $table = TableNames::USERS;
        $sqlQueryForPassword = "SELECT password FROM $table WHERE email = :email;";

        $connection = getDatabaseConnection();
        $getHashedPassword = $connection->prepare($sqlQueryForPassword);
        $getHashedPassword->bindParam(':email', $email);
        $getHashedPassword->execute() or Utils::showMessage(MessageUtils::DATABASE_GET_INFORMATION_ERROR_MESSAGE, false);

        $hashedPassword = "";
        if ($getHashedPassword->rowCount() != 0) {
            $result = $getHashedPassword->fetch(PDO::FETCH_BOTH) or Utils::showMessage(MessageUtils::GET_USER_ERROR_MESSAGE, false);;;
            $hashedPassword = $result['password'];
        }

        return $hashedPassword;
    }

    public static function getUserByUserId($userId)
    {
        $table = TableNames::USERS;
        $sql = "SELECT * FROM $table WHERE user_id = :userId;";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->bindParam(':userId', $userId);
        $resultSet->execute() or Utils::showMessage(MessageUtils::DATABASE_GET_INFORMATION_ERROR_MESSAGE, false);
        $user = $resultSet->fetch(PDO::FETCH_ASSOC) or Utils::showMessage(MessageUtils::GET_USER_ERROR_MESSAGE, false);

        return $user;
    }

    public static function getUserByEmail($email)
    {
        $table = TableNames::USERS;
        $sql = "SELECT * FROM $table WHERE email = :email;";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->bindParam(':email', $email);
        $resultSet->execute() or Utils::showMessage(MessageUtils::DATABASE_GET_INFORMATION_ERROR_MESSAGE, false);
        $user = $resultSet->fetch(PDO::FETCH_ASSOC) or Utils::showMessage(MessageUtils::GET_USER_ERROR_MESSAGE, false);

        return $user;
    }

    public static function getLectureIdByNames($firstNamelecture, $lastNamelecture)
    {
        $connection = getDatabaseConnection();
        $table = TableNames::USERS;

        $selectUserQuery = "SELECT user_id, first_name, last_name FROM $table
                        WHERE first_name=:firstName AND last_name=:lastName;";

        $preparedSql = $connection->prepare($selectUserQuery);
        $preparedSql->bindParam(':firstName', $firstNamelecture);
        $preparedSql->bindParam(':lastName', $lastNamelecture);
        $preparedSql->execute() or Utils::showMessage(MessageUtils::DATABASE_GET_INFORMATION_ERROR_MESSAGE, false);

        if ($preparedSql->rowCount() == 0) {
            return "";
        }

        $firstRow = $preparedSql->fetch(PDO::FETCH_ASSOC) or Utils::showMessage(MessageUtils::GET_USER_ERROR_MESSAGE, false);

        return $firstRow['user_id'];
    }

    public static function getUserByQRCode($qrCode)
    {
        $table = TableNames::USERS;
        $sql = "SELECT * FROM $table WHERE qr_code = :qrCode;";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->bindParam(':qrCode', $qrCode);
        $resultSet->execute() or Utils::showMessage(MessageUtils::DATABASE_GET_INFORMATION_ERROR_MESSAGE, false);
        $user = $resultSet->fetch(PDO::FETCH_ASSOC) or Utils::showMessage(MessageUtils::GET_USER_ERROR_MESSAGE, false);

        return $user;
    }

    public static function getUsersWithouthLoggedinUser($logedinUserEmail)
    {
        $table = TableNames::USERS;
        $sql = "SELECT * FROM $table WHERE email != :email;";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->bindParam(':email', $logedinUserEmail);
        $resultSet->execute() or Utils::showMessage(MessageUtils::DATABASE_GET_INFORMATION_ERROR_MESSAGE, false);
        $users = $resultSet->fetchAll(PDO::FETCH_ASSOC) or Utils::showMessage(MessageUtils::GET_USERS_ERROR_MESSAGE, false);;

        return $users;
    }

    public static function getTopUsers($limit)
    {
        $table = TableNames::USERS;
        $sql = "SELECT * FROM $table ORDER BY points DESC LIMIT {$limit};";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->execute() or Utils::showMessage(MessageUtils::DATABASE_GET_INFORMATION_ERROR_MESSAGE, false);;
        $topUsers = $resultSet->fetchAll(PDO::FETCH_ASSOC) or Utils::showMessage(MessageUtils::GET_TOP_USERS_ERROR_MESSAGE, false);;

        return $topUsers;
    }

    public static function saveUserParkingInfo($userId)
    {
        $table = TableNames::USER_PARKING_INFO;
        $sql = "INSERT INTO $table (parking_date_in, user_id) 
                       VALUES (:parkingDateIn, :userId);";

        $currentDate = date("Y-m-d H:i:s");
        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->bindParam(':parkingDateIn', $currentDate);
        $resultSet->bindParam(':userId', $userId);
        $resultSet->execute() or Utils::showMessage(MessageUtils::DATABASE_SAVE_INFORMATION_ERROR_MESSAGE, false);
    }

    public static function getUserParkingInfo($userId)
    {
        $table = TableNames::USER_PARKING_INFO;
        $sql = "SELECT * FROM $table WHERE user_id = :userId;";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->bindParam(':userId', $userId);
        $resultSet->execute() or Utils::showMessage(MessageUtils::DATABASE_GET_INFORMATION_ERROR_MESSAGE, false);

        if ($resultSet->rowCount() == 0) {
            return "";
        }

        return $resultSet->fetch(PDO::FETCH_ASSOC) or Utils::showMessage(MessageUtils::GET_USER_PARKIN_INFO_ERROR_MESSAGE, false);;;
    }

    public static function deleteUserParkingInfo($userId)
    {
        $table = TableNames::USER_PARKING_INFO;
        $sql = "DELETE FROM $table WHERE user_id = :userId;";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->bindParam(':userId', $userId);
        $resultSet->execute() or Utils::showMessage(MessageUtils::DATABASE_DELETE_INFORMATION_ERROR_MESSAGE, false);
    }

    public static function getParkingSpotById($parkingSpotId)
    {
        $table = TableNames::PARKING_SPOT;
        $sql = "SELECT * FROM $table WHERE parking_spot_id = :parkingSpotId;";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->bindParam(':parkingSpotId', $parkingSpotId);
        $resultSet->execute() or Utils::showMessage(MessageUtils::DATABASE_GET_INFORMATION_ERROR_MESSAGE, false);

        if ($resultSet->rowCount() == 0) {
            return "";
        }

        return $resultSet->fetch(PDO::FETCH_ASSOC) or Utils::showMessage(MessageUtils::GET_USER_PARKIN_SPOT_ERROR_MESSAGE, false);
    }

    public static function saveViewer($viewedUserId)
    {
        $table = TableNames::PROFILE_VIEWER;
        $sql = "INSERT INTO $table (first_name, last_name, email, view_time, user_id) 
                            VALUES (:firstname, :lastname, :email, :viewTime, :userId);";

        $viewerFirstname = $_SESSION['firstName'];
        $viewerLastname = $_SESSION['lastName'];
        $viewerEmail = $_SESSION["email"];
        $viewTime = date("Y-m-d H:i:s");

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->bindParam(':firstname', $viewerFirstname);
        $resultSet->bindParam(':lastname', $viewerLastname);
        $resultSet->bindParam(':email', $viewerEmail);
        $resultSet->bindParam(':viewTime', $viewTime);
        $resultSet->bindParam(':userId', $viewedUserId);
        $resultSet->execute() or Utils::showMessage(MessageUtils::DATABASE_SAVE_INFORMATION_ERROR_MESSAGE, false);
    }

    public static function getUserProfileViewers($userId)
    {
        $table = TableNames::PROFILE_VIEWER;
        $sql = "SELECT * FROM $table WHERE user_id = :userId;;";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->bindParam(':userId', $userId);
        $resultSet->execute() or Utils::showMessage(MessageUtils::DATABASE_GET_INFORMATION_ERROR_MESSAGE, false);
        $viewers = $resultSet->fetchAll(PDO::FETCH_ASSOC) or Utils::showMessage(MessageUtils::GET_VIEWERS_ERROR_MESSAGE, false);

        return $viewers;
    }

    public static function getFreeParkingSpotsNumber()
    {
        $table = TableNames::PARKING_SPOT;
        $sql = "SELECT COUNT(*) FROM $table WHERE is_free = 1;";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->execute() or Utils::showMessage(MessageUtils::DATABASE_GET_INFORMATION_ERROR_MESSAGE, false);
        $numberOfFreeParkingSpots = $resultSet->fetch(PDO::FETCH_ASSOC) or Utils::showMessage(MessageUtils::GET_USER_PARKIN_INFO_ERROR_MESSAGE, false);

        return $numberOfFreeParkingSpots;
    }

    public static function updateUserPoints($userId)
    {
        $table = TableNames::USERS;
        $sql = "UPDATE $table SET points = :points WHERE user_id = :userId;";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->bindParam(':points', $points);
        $resultSet->bindParam(':userId', $userId);
        $resultSet->execute() or Utils::showMessage(MessageUtils::DATABASE_UPDATE_INFORMATION_ERROR_MESSAGE, false);
    }

    public static function getUserCourseIds($userId)
    {
        $table = TableNames::USERS_COURSES;
        $sql = "SELECT course_id FROM $table WHERE user_id = :userId;";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->bindParam(':userId', $userId);
        $resultSet->execute() or Utils::showMessage(MessageUtils::DATABASE_GET_INFORMATION_ERROR_MESSAGE, false);

        if ($resultSet->rowCount() == 0) {
            return "";
        }

        return $resultSet->fetchAll(PDO::FETCH_ASSOC) or Utils::showMessage(MessageUtils::GET_COURSES_ERROR_MESSAGE, false);
    }

    public static function getUserCourses($courseIds)
    {
        $table = TableNames::COURSES;
        $sql = "SELECT * FROM $table WHERE course_id = :courseId;";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);

        $userCourses = array();
        foreach ($courseIds as $course) {
            $resultSet->bindParam(':userId', $course['course_id']);
            $resultSet->execute() or Utils::showMessage(MessageUtils::DATABASE_GET_INFORMATION_ERROR_MESSAGE, false);
            $currentCourse = $resultSet->fetch(PDO::FETCH_ASSOC) or Utils::showMessage(MessageUtils::GET_COURSES_ERROR_MESSAGE, false);
            $userCourses[] = $currentCourse;
        }

        return $userCourses;
    }
}
