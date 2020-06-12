<?php
include '../configuration/db_config.php';
include '../models/user.php';
include '../models/lecturer.php';
include '../models/course.php';
include '../utils/tableNames.php';

class DatabaseQueriesUtils
{
    public static function saveUser(User $user)
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
        $preparedSql->execute() or die("Failed to save user in DB!");
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
        $preparedSql->execute() or die("Failed to save DB!");

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
        $preparedSql->execute() or die("Failed to save in DB!");
    }

    public static function isExistingEmail($email)
    {
        $table = TableNames::USERS;
        $sql = "SELECT * FROM  $table WHERE email = :email;";

        $connection = getDatabaseConnection();
        $preparedSql = $connection->prepare($sql);
        $preparedSql->bindParam(':email', $email);
        $preparedSql->execute() or die("Failed to check if email exist.");

        $result = $preparedSql->fetchAll();

        return count($result) != 0;
    }


    public static function getHashedPassword($email)
    {
        $table = TableNames::USERS;
        $sqlQueryForPassword = "SELECT password FROM $table WHERE email = :email;";

        $connection = getDatabaseConnection();
        $getHashedPassword = $connection->prepare($sqlQueryForPassword);
        $getHashedPassword->bindParam(':email', $email);
        $getHashedPassword->execute() or die("Invalid credentials.");

        $hashedPassword = "";
        if ($getHashedPassword->rowCount() != 0) {
            $result = $getHashedPassword->fetch(PDO::FETCH_BOTH);
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
        $resultSet->execute() or die("Failed to query from DB!");
        $user = $resultSet->fetch(PDO::FETCH_ASSOC) or die("Failed to get user.");

        return $user;
    }

    public static function getUserByEmail($email)
    {
        $table = TableNames::USERS;
        $sql = "SELECT * FROM $table WHERE email = :email;";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->bindParam(':email', $email);
        $resultSet->execute() or die("Failed to query from DB!");
        $user = $resultSet->fetch(PDO::FETCH_ASSOC) or die("Failed to get user.");

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
        $preparedSql->execute() or die("Failed to get lecture from DB!");
        
        if ($preparedSql->rowCount() == 0) {
            return "";
        }

        $firstRow = $preparedSql->fetch(PDO::FETCH_ASSOC);

        return $firstRow['user_id'];
    }

    public static function getUserByQRCode($qrCode)
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

    public static function getUsersWithouthLoggedinUser($logedinUserEmail)
    {
        $table = TableNames::USERS;
        $sql = "SELECT * FROM $table WHERE email != :email;";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->bindParam(':email', $logedinUserEmail);
        $resultSet->execute() or die("Failed to query from DB!");
        $users = $resultSet->fetchAll(PDO::FETCH_ASSOC) or die("Failed to get users.");

        return $users;
    }

    public static function getTopUsers($limit)
    {
        $table = TableNames::USERS;
        $sql = "SELECT * FROM $table ORDER BY points DESC LIMIT {$limit};";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->execute() or die("Failed to query from DB!");
        $topUsers = $resultSet->fetchAll(PDO::FETCH_ASSOC) or die("Failed to get top users!");

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
        $resultSet->execute() or die("Failed to query from DB!");
    }

    public static function getUserParkingInfo($userId)
    {
        $table = TableNames::USER_PARKING_INFO;
        $sql = "SELECT * FROM $table WHERE user_id = :userId;";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->bindParam(':userId', $userId);
        $resultSet->execute() or die("Failed to query from DB!");

        if ($resultSet->rowCount() == 0) {
            return "";
        }

        return $resultSet->fetch(PDO::FETCH_ASSOC) or die("Failed to get user parking info.");
    }

    public static function deleteUserParkingInfo($userId)
    {
        $table = TableNames::USER_PARKING_INFO;
        $sql = "DELETE FROM $table WHERE user_id = :userId;";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->bindParam(':userId', $userId);
        $resultSet->execute() or die("Failed to query from DB!");
    }

    public static function getParkingSpotById($parkingSpotId)
    {
        $table = TableNames::PARKING_SPOT;
        $sql = "SELECT * FROM $table WHERE parking_spot_id = :parkingSpotId;";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->bindParam(':parkingSpotId', $parkingSpotId);
        $resultSet->execute() or die("Failed to query from DB!");

        if ($resultSet->rowCount() == 0) {
            return "";
        }

        return $resultSet->fetch(PDO::FETCH_ASSOC) or die("Failed to get user.");
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
        $resultSet->execute() or die("Failed to query from DB!");
    }

    public static function getUserProfileViewers($userId)
    {
        $table = TableNames::PROFILE_VIEWER;
        $sql = "SELECT * FROM $table WHERE user_id = :userId;;";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->bindParam(':userId', $userId);
        $resultSet->execute() or die("Failed to query from DB!");
        $viewers = $resultSet->fetchAll(PDO::FETCH_ASSOC) or die("Failed to get viewers!");

        return $viewers;
    }

    public static function getFreeParkingSpotsNumber()
    {
        $table = TableNames::PARKING_SPOT;
        $sql = "SELECT COUNT(*) FROM $table WHERE is_free = 1;";

        $connection = getDatabaseConnection();
        $resultSet = $connection->prepare($sql);
        $resultSet->execute() or die("Failed to query from DB!");
        $numberOfFreeParkingSpots = $resultSet->fetch(PDO::FETCH_ASSOC) or die("Failed to get courses.");

        return $numberOfFreeParkingSpots;
    }

    public static function updateUserPoints($userId, $parkingDateIn, $status)
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
            $lecture = DatabaseQueriesUtils::getLectureAtThatTime($courses);
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

    public static function getUserCourseIds($userId)
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

    public static function getUserCourses($courseIds)
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

    public static function getLectureAtThatTime($courses)
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
}
